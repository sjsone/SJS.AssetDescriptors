<?php

namespace SJS\AssetDescriptors\Descriptor;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManager;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\Image;
use SJS\AssetDescriptors\API;
use SJS\AssetDescriptors\API\Client;
use SJS\AssetDescriptors\API\ErrorResponse;


#[Flow\Proxy(false)]
class ImageCaptionDescriptor extends AbstractDescriptor
{
    protected Client $client;

    protected array $supportedMediaTypes = [
        "png" => "image/png",
        "jpeg" => "image/jpeg",
        "gif" => "image/gif",
        "webp" => "image/webp",
    ];

    public function __construct(
        string $name,
        array $options,
        ObjectManager $objectManager
    ) {
        parent::__construct($name, $options, $objectManager);
        $this->client = $this->objectManager->get(Client::class);
    }

    public function describe(Asset $asset)
    {
        if (!($asset instanceof Image)) {
            return;
        }

        if (!in_array($asset->getMediaType(), $this->supportedMediaTypes)) {
            return;
        }

        $description = $this->getDescriptionForImage($asset);
        if (!$description) {
            return;
        }

        $this->saveDescription($asset, $description);
    }

    protected function getDescriptionForImage(Image $image): ?string
    {
        if ($image instanceof ImageVariant) {
            return null;
        }

        $stream = $image->getResource()->getStream();
        if (!$stream) {
            return null;
        }

        $text = $this->options["prompt"];

        $payload = new API\Chat\Completions\Request([
            API\Chat\Completions\Request\Message::User([
                API\Chat\Completions\Request\Message\Content\Text::fromText($text),
                API\Chat\Completions\Request\Message\Content\ImageURL::fromStream($stream),
            ])
        ]);

        $response = $this->client->request($payload);
        if (!$response) {
            return null;
        }

        if ($response instanceof ErrorResponse) {
            throw new \Exception("API Responded with Error: " . $response->message);
        }

        if (!($response instanceof API\Chat\Completions\Response)) {
            return null;
        }

        return $response->getFirstChoice()?->message->content;
    }

    protected function saveDescription(Image $image, string $description)
    {

        $image->setCaption($description);
        $this->assetRepository->update($image);
    }
}