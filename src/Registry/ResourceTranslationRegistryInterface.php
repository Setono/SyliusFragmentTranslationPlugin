<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Registry;

use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

interface ResourceTranslationRegistryInterface
{
    /**
     * @return ResourceTranslation[]
     */
    public function all(): array;

    public function register(ResourceTranslation $template): void;
}
