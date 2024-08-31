<?php
namespace SJS\AssetDescriptors\API\Chat\Completions;

use Neos\Flow\Annotations as Flow;
use SJS\AssetDescriptors\API\AbstractRequest;

#[Flow\Proxy(false)]
class Request extends AbstractRequest
{
    public function __construct(
        public readonly array $messages,
        public readonly string $model = "gpt-4o-mini",
        public readonly int $maxTokens = 300,
    ) {
    }

    function jsonSerialize()
    {
        return [
            "model" => $this->model,
            "messages" => array_map(fn($m) => $m, $this->messages),
            "max_tokens" => $this->maxTokens
        ];
    }
}