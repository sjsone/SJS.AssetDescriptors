<?php

namespace SJS\AssetDescriptors\API;

use Neos\Flow\Annotations as Flow;
use GuzzleHttp;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Model\ImageVariant;

#[Flow\Scope("singleton")]
class Client
{

    #[Flow\InjectConfiguration("api.token")]
    protected string $token;

    protected GuzzleHttp\Client $client;

    public function initializeObject()
    {
        $this->client = new GuzzleHttp\Client([
            GuzzleHttp\RequestOptions::HEADERS => [
                "Authorization" => "Bearer $this->token",
                "Content-Type" => "application/json"
            ],
            GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 10,
            GuzzleHttp\RequestOptions::READ_TIMEOUT => 10,
            GuzzleHttp\RequestOptions::HTTP_ERRORS => false
        ]);
    }

    public function request(AbstractRequest $abstractRequest): ?AbstractResponse
    {
        $url = $this->urlForRequest($abstractRequest);
        $response = $this->client->post($url, [
            GuzzleHttp\RequestOptions::JSON => $abstractRequest
        ]);

        $responseBody = json_decode((string) $response->getBody(), true);

        if (isset($responseBody["error"])) {
            return $this->createErrorResponse($responseBody["error"]);
        }

        return $this->createResponseForRequest($abstractRequest, $responseBody);
    }

    protected function createErrorResponse(array $errorData): ErrorResponse
    {
        return match ($errorData["type"]) {
            "invalid_request_error" => InvalidRequestErrorResponse::fromArray($errorData),
            default => ErrorResponse::fromArray($errorData),
        };
    }

    protected function createResponseForRequest(AbstractRequest $abstractRequest, array $responseBody): AbstractResponse
    {
        if ($abstractRequest instanceof Chat\Completions\Request) {
            return Chat\Completions\Response::fromArray($responseBody);
        }

        throw new \Exception("Response for Request of type " . $abstractRequest::class . " is not known");
    }

    protected function urlForRequest(AbstractRequest $abstractRequest): string
    {
        if ($abstractRequest instanceof Chat\Completions\Request) {
            return "https://api.openai.com/v1/chat/completions";
        }

        throw new \Exception("Could not resolve URL for request of type " . $abstractRequest::class);
    }
}