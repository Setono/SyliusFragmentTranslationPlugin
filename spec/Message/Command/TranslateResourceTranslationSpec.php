<?php

declare(strict_types=1);

namespace spec\Setono\SyliusFragmentTranslationPlugin\Message\Command;

use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;

class TranslateResourceTranslationSpec extends AbstractCommandSpec
{
    public function let(): void
    {
        $this->beConstructedWith(self::getSharedResourceTranslation(), 1);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(TranslateResourceTranslation::class);
    }

    public function it_returns_correct_values(): void
    {
        $this->getResourceTranslation()->shouldReturn(self::getSharedResourceTranslation());
        $this->getId()->shouldReturn(1);
    }
}
