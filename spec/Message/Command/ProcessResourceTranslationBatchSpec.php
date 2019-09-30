<?php

declare(strict_types=1);

namespace spec\Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\DoctrineORMBatcher\Batch\BatchInterface;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;

class ProcessResourceTranslationBatchSpec extends AbstractCommandSpec
{
    public function let(BatchInterface $batch): void
    {
        $this->beConstructedWith(self::getSharedResourceTranslation(), $batch);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProcessResourceTranslationBatch::class);
    }

    public function it_returns_correct_values(BatchInterface $batch): void
    {
        $this->getResourceTranslation()->shouldReturn(self::getSharedResourceTranslation());
        $this->getBatch()->shouldReturn($batch);
    }
}
