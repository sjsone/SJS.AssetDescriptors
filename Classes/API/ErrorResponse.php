<?php
namespace SJS\AssetDescriptors\API;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class ErrorResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $message,
        public readonly mixed $param,
        public readonly mixed $code,
    ) {
    }

    static function fromArray(array $data)
    {
        return new self($data["message"], $data["param"], $data["code"]);
    }
}