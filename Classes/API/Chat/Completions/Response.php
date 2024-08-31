<?php
namespace SJS\AssetDescriptors\API\Chat\Completions;

use Neos\Flow\Annotations as Flow;
use SJS\AssetDescriptors\API\AbstractResponse;
use SJS\AssetDescriptors\API\Chat\Completions\Response\Choice;

#[Flow\Proxy(false)]
class Response extends AbstractResponse
{
    protected function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly string $created,
        public readonly string $model,
        public readonly array $choices,
        public readonly string $systemFingerprint,
    ) {
    }

    public function hasChoices()
    {
        return count($this->choices) > 0;
    }

    public function getFirstChoice(): ?Choice
    {
        return $this->choices[0] ?? null;
    }

    static function fromArray(array $data)
    {
        return new self(
            $data['id'],
            $data['object'],
            $data['created'],
            $data['model'],
            array_map(fn($d) => Choice::fromArray($d), $data['choices']),
            $data['system_fingerprint']
        );
    }

    static function fromJson(string $json)
    {
        return self::fromArray(json_decode($json, true));
    }
}