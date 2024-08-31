<?php
namespace SJS\AssetDescriptors\Matcher;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\Image;

class TypeMatcher implements ProtectedContextAwareInterface
{

    public function isImage(Asset $asset): bool
    {
        return $asset instanceof Image;
    }

    function allowsCallOfMethod($methodName)
    {
        return true;
    }
}