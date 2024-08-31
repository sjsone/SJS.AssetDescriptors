<?php

namespace SJS\AssetDescriptors\API\Chat\Completions\Request\Message\Content;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class ImageURL implements \JsonSerializable
{
    protected function __construct(
        public readonly array $imageUrl
    ) {
    }

    static function fromURL(string $url)
    {
        return new self([
            'url' => $url
        ]);
    }

    static function fromStream(mixed $stream)
    {
        $content = stream_get_contents($stream);
        if (!$content) {
            throw new \Exception("Could not get contents for stream");
        }

        $base64Image = base64_encode($content);

        return new self([
            'url' => "data:image/jpeg;base64,$base64Image"
        ]);
    }

    function jsonSerialize()
    {
        return [
            "type" => "image_url",
            "image_url" => $this->imageUrl
        ];
    }
}