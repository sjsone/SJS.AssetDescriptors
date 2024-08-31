<?php

namespace SJS\AssetDescriptors\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Repository\AssetRepository;
use SJS\AssetDescriptors\API\Client;
use SJS\AssetDescriptors\Manager\DescriptionManager;


#[Flow\Scope("singleton")]
class AltCommandController extends CommandController
{
    #[Flow\Inject]
    protected AssetRepository $assetRepository;

    #[Flow\Inject]
    protected Client $client;

    #[Flow\Inject]
    protected DescriptionManager $descriptionManager;

    public function testCommand(string $assetIdentifier)
    {
        $asset = $this->assetRepository->findByIdentifier($assetIdentifier);
        if (!($asset instanceof Image)) {
            return;
        }

        $this->descriptionManager->describeAsset($asset);
    }
}