<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

final class TranslateResourceTranslation implements CommandInterface
{
    private ResourceTranslation $resourceTranslation;

    private int $id;

    public function __construct(ResourceTranslation $resourceTranslation, int $id)
    {
        $this->resourceTranslation = $resourceTranslation;
        $this->id = $id;
    }

    public function getResourceTranslation(): ResourceTranslation
    {
        return $this->resourceTranslation;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
