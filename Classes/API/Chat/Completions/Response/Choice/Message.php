<?php
namespace SJS\AssetDescriptors\API\Chat\Completions\Response\Choice;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class Message
{
    protected function __construct(
        public readonly string $role,
        public readonly string $content,
        public readonly mixed $refusal,
    ) {
    }

    static function fromArray(array $data)
    {
        return new self(
            $data['role'],
            $data['content'],
            $data['refusal'],
        );
    }

    static function fromJson(string $json)
    {
        return self::fromArray(json_decode($json, true));
    }
}