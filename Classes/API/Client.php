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

        $responseBody = (string) $response->getBody();

        return Chat\Completions\Response::fromJson($responseBody);
    }

    protected function urlForRequest(AbstractRequest $abstractRequest): string
    {
        if ($abstractRequest instanceof Chat\Completions\Request) {
            return "https://api.openai.com/v1/chat/completions";
        }

        throw new \Exception("Could not resolve URL for request of type " . $abstractRequest::class);
    }
}