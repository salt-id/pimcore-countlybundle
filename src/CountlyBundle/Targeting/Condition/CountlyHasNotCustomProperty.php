<?php
/**
 * pimcore-countlybundle
 * PT. Ako Media Asia (https://salt.id/)
 * Copyright 2020
 *
 * Licensed under The MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource CountlyHasNotCustomProperty.php
 * @copyright Copyright 2020, PT. Ako Media Asia
 * @author yuliusardian
 * @since 20/11/20
 * @time 12.41
 *
 */

namespace SaltId\CountlyBundle\Targeting\Condition;

use Pimcore\Targeting\Condition\AbstractVariableCondition;
use Pimcore\Targeting\Condition\ConditionInterface;
use Pimcore\Targeting\DataProviderDependentInterface;
use Pimcore\Targeting\Model\VisitorInfo;
use SaltId\CountlyBundle\Countly\Api\v1\UserProfiles\UserProfiles;
use SaltId\CountlyBundle\Targeting\DataProvider\CountlyCustomProperty;
use SaltId\CountlyBundle\Targeting\DataProvider\CountlyFunnelComplete;

class CountlyHasNotCustomProperty extends AbstractVariableCondition implements DataProviderDependentInterface
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
        $configs['attributeKey'] = $config['attributeKey'] ?? null;

        return new self($configs);
    }

    public function canMatch(): bool
    {
        return null !== $this->configs;
    }

    public function match(VisitorInfo $visitorInfo): bool
    {
        $id = $visitorInfo->getRequest()->getSession()->get('cly_id', null);

        if (!$id) {
            return false;
        }

        $userDetailParams = [
            'query' => [
                'uid' => $id,
                'period' => 'hour'
            ]
        ];

        $userDetails = $this->userProfile->getUserDetails($userDetailParams);
        $custom = $userDetails['custom'] ?? [];
        // conditions not matched if funnel empty.
        if (!$custom) {
            return false;
        }

        $attributeKey = $this->configs['attributeKey'] ?? null;

        if (!$attributeKey) {
            return false;
        }

        if ($custom[$attributeKey]) {
            return false;
        }

        $this->setMatchedVariable('attributeKey', $attributeKey);
        return true;
    }

    public function getDataProviderKeys(): array
    {
        return [
            CountlyCustomProperty::PROVIDER_KEY
        ];
    }
}
