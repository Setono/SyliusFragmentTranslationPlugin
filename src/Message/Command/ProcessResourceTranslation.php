<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

final class ProcessResourceTranslation implements CommandInterface
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
