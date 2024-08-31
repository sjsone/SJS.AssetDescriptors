<?php
namespace SJS\AssetDescriptors\Matcher;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Media\Domain\Model\Asset;


class TagMatcher implements ProtectedContextAwareInterface
{

    public function hasTag(Asset $asset, string $tagLabel): bool
    {
        $tags = $asset->getTags();
        foreach ($tags as $tag) {
            /** @var \Neos\Media\Domain\Model\Tag $tag */
            if ($tag->getLabel() === $tagLabel) {
                return true;
            }
        }
        return false;
    }

    public function hasTagWithPrefix(Asset $asset, string $prefix): bool
    {
        $tags = $asset->getTags();
        foreach ($tags as $tag) {
            /** @var \Neos\Media\Domain\Model\Tag $tag */
            if (str_starts_with($tag->getLabel(), $prefix)) {
                return true;
            }
        }
        return false;
    }

    public function hasTagWithSuffix(Asset $asset, string $suffix): bool
    {
        $tags = $asset->getTags();
        foreach ($tags as $tag) {
            /** @var \Neos\Media\Domain\Model\Tag $tag */
            if (str_ends_with($tag->getLabel(), $suffix)) {
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