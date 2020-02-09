<?php

namespace SaltId\CountlyBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

class CountlyBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    public function getNiceName()
    {
        return 'Countly Bundle';
    }

    public function getDescription()
    {
        return 'Countly Analytics with Pimcore';
    }

    public function getJsPaths()
    {
        return [
            '/bundles/countly/js/pimcore/targeting/conditions.js',
            '/bundles/countly/js/pimcore/startup.js'
        ];
    }

    protected function getComposerPackageName(): string
    {
        return 'saltid/pimcore-countlybundle';
    }

    public function getInstaller()
    {
        return $this->container->get(Installer::class);
    }
}