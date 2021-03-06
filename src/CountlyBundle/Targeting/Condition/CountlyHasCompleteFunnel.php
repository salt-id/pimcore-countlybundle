<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 31/01/2020
 * Time: 19:00
 */

namespace SaltId\CountlyBundle\Targeting\Condition;

use Pimcore\Targeting\Condition\AbstractVariableCondition;
use Pimcore\Targeting\Condition\ConditionInterface;
use Pimcore\Targeting\DataProviderDependentInterface;
use Pimcore\Targeting\Model\VisitorInfo;
use SaltId\CountlyBundle\Countly\Api\v1\UserProfiles\UserProfiles;
use SaltId\CountlyBundle\Targeting\DataProvider\CountlyFunnelComplete;

class CountlyHasCompleteFunnel extends AbstractVariableCondition implements DataProviderDependentInterface
{
    /**
     * @var array $configs
     */
    private $configs;

    /** @var UserProfiles $userProfile */
    private $userProfile;

    public function __construct(array $configs = [])
    {
        $this->configs = $configs;
        $this->userProfile = new UserProfiles();
    }

    public static function fromConfig(array $config)
    {
        $configs['funnels'] = $config['funnels'] ?? null;
        $configs['steps'] = $config['steps'] ?? null;

        return new self($configs);
    }

    public function canMatch(): bool
    {
        return null !== $this->configs;
    }

    public function match(VisitorInfo $visitorInfo): bool
    {
        $currentFunnels = $visitorInfo->getRequest()->getSession()->get(CountlyFunnelComplete::PROVIDER_KEY);

        $funnelsFromConfig = $this->configs['funnels'] ?? null;
        $stepsFromConfig = $this->configs['steps'] ?? null;

        $key = sprintf('%s_%s',$funnelsFromConfig, $stepsFromConfig);
        if (
            null !== $currentFunnels &&
            $currentFunnel = $currentFunnels[$key]
        ) {
            return $currentFunnel;
        }

        // check cookies if has 'cly_id' hit countly api 'user_details' with MongoDB Query
        // {"did":{"$in":["cly_id"]}}
        $cookies = $visitorInfo->getRequest()->cookies;
        if (!$cookies->has('mid')) {
            return false;
        }

        $clyId = $cookies->get('mid');
        // build a MongoDB query.
        $mongoDbQueryUidIn = [
            'did' => [
                '$in' => [$clyId]
            ]
        ];

        // assign $mongoDbQueryUidIn into query.
        $params = [
            'query' => json_encode($mongoDbQueryUidIn)
        ];

        // assign $params into guzzle query string.
        $params = [
            'query' => $params
        ];

        $findUser = $this->userProfile->getUserDetails($params);
        if (
            !isset($findUser['iTotalDisplayRecords']) ||
            $findUser['iTotalDisplayRecords'] === null ||
            $findUser['iTotalDisplayRecords'] === 0
        ) {
            return false;
        }

        $aaData = $findUser['aaData'] ?? [];
        $user = $aaData[0] ?? [];
        $id = $user['_id'] ?? 0;

        $userDetailParams = [
            'query' => [
                'uid' => $id,
                'period' => 'hour'
            ]
        ];

        $findFunnels = $this->userProfile->getUserDetails($userDetailParams);
        $funnels = $findFunnels['funnels'] ?? [];
        // conditions not matched if funnel empty.
        if (!$funnels) {
            return false;
        }

        $matchedFunnel = null;
        foreach ($funnels as $funnel) {
            if ($funnel['_id'] === $funnelsFromConfig) {
                $matchedFunnel = $funnel;
                break;
            }
        }

        $stepsCount = count($matchedFunnel['steps']) ?? 0;
        $userCurrentSteps = $matchedFunnel['step'] ?? 0;
        $userCurrentSteps = ($userCurrentSteps > 0 ? $userCurrentSteps - 1 : $userCurrentSteps);
        $userCurrentStepName = $matchedFunnel['steps'][$userCurrentSteps] ?? '';

        // @todo if in final steps or complete should ignore ?

        // check if $stepsFromConfig === $userCurrentStepName then condition matched. return true.
        if ($stepsFromConfig === $userCurrentStepName) {
            $currentFunnels = array_merge_recursive($currentFunnels ?? [], [
                $funnelsFromConfig . '_' . $stepsFromConfig => true
            ]);

            $visitorInfo->getRequest()->getSession()->set('cly_id', $id);
            $visitorInfo->getRequest()->getSession()->set(CountlyFunnelComplete::PROVIDER_KEY, $currentFunnels);

            $this->setMatchedVariable('funnels', $matchedFunnel);
            return true;
        }

        return false;
    }

    public function getDataProviderKeys(): array
    {
        return [
            CountlyFunnelComplete::PROVIDER_KEY
        ];
    }
}
