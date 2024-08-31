<?php

namespace SJS\AssetDescriptors\API\Chat\Completions\Request\Message\Content;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class Text implements \JsonSerializable
{
    protected function __construct(
        public readonly string $text
    ) {
    }

    static function fromText(string $text)
    {
        return new self($text);
    }

    function jsonSerialize()
    {
        return [
            "type" => "text",
            "text" => $this->text
        ];
    }
}