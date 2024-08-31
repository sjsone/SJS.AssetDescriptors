<?php
namespace SJS\AssetDescriptors\Matcher;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;

class CollectionMatcher implements ProtectedContextAwareInterface
{

    public function inCollection(Asset $asset, string $collectionTitle): bool
    {
        foreach ($asset->getAssetCollections() as $collection) {
            /** @var AssetCollection $collection */
            if ($collection->getTitle() === $collectionTitle) {
                return true;
            }
        }

        return false;
    }

    function allowsCallOfMethod($methodName)
    {
        return true;
    }
}