<?php

namespace SJS\AssetDescriptors\Manager;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManager;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Repository\AssetRepository;
use SJS\AssetDescriptors\API\Client;
use Neos\Eel\CompilingEvaluator;
use SJS\AssetDescriptors\Descriptor\AbstractDescriptor;
use Neos\Eel\Utility;

#[Flow\Scope("singleton")]
class DescriptionManager
{
    #[Flow\Inject]
    protected ObjectManager $objectManager;


    #[Flow\InjectConfiguration(path: "descriptors")]
    protected array $descriptorsConfiguration;

    #[Flow\InjectConfiguration(path: "directives")]
    protected array $directivesConfiguration;

    #[Flow\InjectConfiguration(path: "matcher")]
    protected array $matcherConfiguration;

    #[Flow\Inject(lazy: false)]
    protected CompilingEvaluator $compilingEvaluator;


    /**
     * @var array<string, Directive>
     */
    protected array $directives;

    /**
     * @var array<string, AbstractDescriptor>
     */
    protected array $descriptors;


    public function initializeObject()
    {
        $this->initializeDescriptors();
        $this->initializeConfiguration();
    }

    protected function initializeDescriptors()
    {
        foreach ($this->descriptorsConfiguration as $name => $configuration) {
            $this->descriptors[$name] = AbstractDescriptor::create($name, $configuration, $this->objectManager);
        }
    }

    protected function initializeConfiguration()
    {
        foreach ($this->directivesConfiguration as $name => $configuration) {
            $this->directives[$name] = Directive::fromConfiguration($name, $configuration, $this->descriptors);
        }
    }

    public function describeAssetCreated(Asset $asset)
    {
        $this->describeAsset($asset);
    }

    public function describeAssetUpdated(Asset $asset)
    {
        $this->describeAsset($asset);
    }

    public function describeAsset(Asset $asset)
    {
        $directive = $this->findMatchingDirective($asset);
        if (!$directive) {
            return;
        }

        foreach ($directive->descriptors as $descriptor) {
            /** @var AbstractDescriptor $descriptor */
            $descriptor->describe($asset);
        }
    }

    protected function buildContextForFindingDirective(Asset $asset): array
    {
        return [
            "asset" => $asset,
            "directive" => $this,
        ];
    }

    protected function doesDirectiveMatchAsset(Directive $directive, Asset $asset, array $context): bool
    {
        $result = Utility::evaluateEelExpression(
            '${' . $directive->matcherExpression . '}',
            $this->compilingEvaluator,
            $context,
            $this->matcherConfiguration["context"] ?? []
        );

        return $result || false;
    }

    protected function findMatchingDirective(Asset $asset): ?Directive
    {
        $context = $this->buildContextForFindingDirective($asset);

        foreach ($this->directives as $directive) {
            $result = $this->doesDirectiveMatchAsset($directive, $asset, $context);
            if ($result) {
                return $directive;
            }
        }

        return null;
    }
}