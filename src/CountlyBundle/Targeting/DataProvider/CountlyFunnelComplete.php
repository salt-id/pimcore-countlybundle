<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 31/01/2020
 * Time: 19:02
 */

namespace SaltId\CountlyBundle\Targeting\DataProvider;

use Pimcore\Targeting\DataProvider\DataProviderInterface;
use Pimcore\Targeting\Model\VisitorInfo;

class CountlyFunnelComplete implements DataProviderInterface
{
    const PROVIDER_KEY = 'countly_funnel_complete';

    public function load(VisitorInfo $visitorInfo)
    {
        if ($visitorInfo->has(self::PROVIDER_KEY)) {
            // abort if there already is data for this provider
            return;
        }
        $visitorInfo->set(self::PROVIDER_KEY, "somethinghere");
    }
}