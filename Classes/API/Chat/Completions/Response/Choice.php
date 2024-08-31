<?php
namespace SJS\AssetDescriptors\API\Chat\Completions\Response;

use Neos\Flow\Annotations as Flow;
use SJS\AssetDescriptors\API\Chat\Completions\Response\Choice\Message;

#[Flow\Proxy(false)]
class Choice
{
    protected function __construct(
        public readonly int $index,
        public readonly Message $message,
        public readonly mixed $logprobs,
        public readonly string $finishReason,
    ) {
    }

    static function fromArray(array $data)
    {
        return new self(
            $data['index'],
            Message::fromArray($data['message']),
            $data['logprobs'],
            $data['finish_reason'],
        );
    }

    static function fromJson(string $json)
    {
        return self::fromArray(json_decode($json, true));
    }
}