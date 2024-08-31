<?php

namespace SJS\AssetDescriptors\Manager;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Repository\AssetRepository;
use SJS\AssetDescriptors\API\Client;


#[Flow\Proxy(false)]
class Directive
{
    protected function __construct(
        public readonly string $name,
        public readonly array $when,
        public readonly string $matcherExpression,
        public readonly array $descriptors
    ) {
    }

    static function fromConfiguration(string $name, array $configuration, array $availableDescriptors)
    {
        $descriptors = [];
        foreach ($configuration['descriptors'] as $descriptorName) {
            if (!$descriptorName) {
                continue;
            }

            $descriptors[] = $availableDescriptors[$descriptorName];
        }

        return new self(
            $name,
            $configuration['when'] ?? [],
            $configuration['match'],
            $descriptors
        );
    }
}