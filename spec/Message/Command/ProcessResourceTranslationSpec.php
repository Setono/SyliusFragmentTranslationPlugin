<?php

declare(strict_types=1);

namespace spec\Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslation;

class ProcessResourceTranslationSpec extends AbstractCommandSpec
{
    public function let(): void
    {
        $this->beConstructedWith(self::getSharedResourceTranslation());
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProcessResourceTranslation::class);
    }

    public function it_returns_resource_translation(): void
    {
        $this->getResourceTranslation()->shouldReturn(self::getSharedResourceTranslation());
    }
}
