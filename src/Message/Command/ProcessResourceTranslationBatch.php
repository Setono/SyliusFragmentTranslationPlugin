<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\DoctrineORMBatcher\Batch\BatchInterface;
use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

final class ProcessResourceTranslationBatch implements CommandInterface
{
    /** @var ResourceTranslation */
    private $resourceTranslation;

    /** @var BatchInterface */
    private $batch;

    public function __construct(ResourceTranslation $resourceTranslation, BatchInterface $batch)
    {
        $this->resourceTranslation = $resourceTranslation;
        $this->batch = $batch;
    }

    public function getResourceTranslation(): ResourceTranslation
    {
        return $this->resourceTranslation;
    }

    public function getBatch(): BatchInterface
    {
        return $this->batch;
    }
}
