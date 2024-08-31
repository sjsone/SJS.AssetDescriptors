<?php
namespace SJS\AssetDescriptors\API\Chat\Completions\Request;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class Message implements \JsonSerializable
{
    public function __construct(
        public readonly string $role,
        public readonly array $content
    ) {
    }

    public static function User(array $content): self
    {
        return new self("user", $content);
    }

    function jsonSerialize()
    {
        return [
            "role" => $this->role,
            "content" => array_map(fn($c) => $c, $this->content)
        ];
    }
}