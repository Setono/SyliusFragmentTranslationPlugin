<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Registry;

use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

final class ResourceTranslationRegistry implements ResourceTranslationRegistryInterface
{
    private $resourceTranslations = [];

    public function all(): array
    {
        return $this->resourceTranslations;
    }

    public function register(ResourceTranslation $resourceTranslation): void
    {
        $this->resourceTranslations[$resourceTranslation->getResource()] = $resourceTranslation;
    }
}
