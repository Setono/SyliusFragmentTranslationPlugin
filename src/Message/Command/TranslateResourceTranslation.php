<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\Translation\ResourceTranslation;

final class TranslateResourceTranslation
{
    /**
     * @var ResourceTranslation
     */
    private $resourceTranslation;

    /**
     * @var int
     */
    private $id;

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
