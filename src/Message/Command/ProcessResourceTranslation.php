<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\Translation\ResourceTranslation;

final class ProcessResourceTranslation
{
    /**
     * @var ResourceTranslation
     */
    private $resourceTranslation;

    public function __construct(ResourceTranslation $resourceTranslation)
    {
        $this->resourceTranslation = $resourceTranslation;
    }

    public function getResourceTranslation(): ResourceTranslation
    {
        return $this->resourceTranslation;
    }
}
