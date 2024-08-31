<?php

namespace SJS\AssetDescriptors;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\AssetService;
use SJS\AssetDescriptors\Manager\DescriptionManager;

class Package extends BasePackage
{
    /**
     * @inheritdoc
     *
     * @param Bootstrap $bootstrap The current bootstrap
     *
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(AssetService::class, 'assetCreated', DescriptionManager::class, 'describeAssetCreated');
        $dispatcher->connect(AssetService::class, 'assetUpdated', DescriptionManager::class, 'describeAssetUpdated');
    }
}