<?php

namespace SJS\AssetDescriptors\Descriptor;

use Neos\Flow\ObjectManagement\ObjectManager;
use Neos\Media\Domain\Model\Asset;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Repository\AssetRepository;

#[Flow\Proxy(false)]
abstract class AbstractDescriptor
{
    protected AssetRepository $assetRepository;

    public function __construct(
        protected string $name,
        protected array $options,
        protected ObjectManager $objectManager
    ) {
        $this->assetRepository = $this->objectManager->get(AssetRepository::class);
    }

    abstract public function describe(Asset $asset);

    static function create(string $name, array $configuration, ObjectManager $objectManager): AbstractDescriptor
    {
        $options = $configuration['options'] ?? [];

        $descriptor = new $configuration['class']($name, $options, $objectManager);
        if (!($descriptor instanceof AbstractDescriptor)) {
            throw new \Exception($configuration['class'] . " does not extend AbstractDescriptor");
        }

        return $descriptor;
    }
}