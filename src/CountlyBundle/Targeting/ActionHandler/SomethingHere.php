<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 03/02/2020
 * Time: 15:37
 */

namespace SaltId\CountlyBundle\Targeting\ActionHandler;

use Pimcore\Model\Tool\Targeting\Rule;
use Pimcore\Targeting\ActionHandler\ActionHandlerInterface;
use Pimcore\Targeting\DataProviderDependentInterface;
use Pimcore\Targeting\Model\VisitorInfo;

class SomethingHere implements ActionHandlerInterface, DataProviderDependentInterface
{
    public function apply(VisitorInfo $visitorInfo, array $action, Rule $rule = null)
    {
        // TODO: Implement apply() method.
    }

    public function getDataProviderKeys(): array
    {
        // TODO: Implement getDataProviderKeys() method.
    }
}