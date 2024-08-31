<?php
namespace SJS\AssetDescriptors\API;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class InvalidRequestErrorResponse extends ErrorResponse
{
    static function fromArray(array $data)
    {
        return new self($data["message"], $data["param"], $data["code"]);
    }
}