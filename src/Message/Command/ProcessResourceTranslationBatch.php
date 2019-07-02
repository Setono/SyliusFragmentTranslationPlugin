<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

final class ProcessResourceTranslationBatch implements CommandInterface
{
    /** @var ResourceTranslation */
    private $resourceTranslation;

    /** @var int */
    private $offsetId;

    /** @var int */
    private $limit;

    public function __construct(ResourceTranslation $resourceTranslation, int $offsetId, int $limit)
    {
        $this->resourceTranslation = $resourceTranslation;
        $this->offsetId = $offsetId;
        $this->limit = $limit;
    }

    public function getResourceTranslation(): ResourceTranslation
    {
        return $this->resourceTranslation;
    }

    public function getOffsetId(): int
    {
        return $this->offsetId;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
