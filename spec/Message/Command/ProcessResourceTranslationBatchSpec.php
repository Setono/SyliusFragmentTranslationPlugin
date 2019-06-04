<?php

declare(strict_types=1);

namespace spec\Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;

class ProcessResourceTranslationBatchSpec extends AbstractCommandSpec
{
    public function let(): void
    {
        $this->beConstructedWith(self::getSharedResourceTranslation(), 1, 1);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProcessResourceTranslationBatch::class);
    }

    public function it_returns_correct_values(): void
    {
        $this->getResourceTranslation()->shouldReturn(self::getSharedResourceTranslation());
        $this->getOffsetId()->shouldReturn(1);
        $this->getLimit()->shouldReturn(1);
    }
}
