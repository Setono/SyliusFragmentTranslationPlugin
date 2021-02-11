<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Registry;

use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

final class ResourceTranslationRegistry implements ResourceTranslationRegistryInterface
{
    /** @var array<string, ResourceTranslation> */
    private array $resourceTranslations = [];

    public function all(): array
    {
        return $this->resourceTranslations;
    }

    public function register(ResourceTranslation $resourceTranslation): void
    {
        $this->resourceTranslations[$resourceTranslation->getResource()] = $resourceTranslation;
    }

    public function findByClass(string $class): ?ResourceTranslation
    {
        foreach ($this->resourceTranslations as $resourceTranslation) {
            if ($resourceTranslation->getClass() === $class) {
                return $resourceTranslation;
            }
        }

        return null;
    }
}
