<?php

declare(strict_types=1);

namespace spec\Setono\SyliusFragmentTranslationPlugin\Message\Command;

use PhpSpec\ObjectBehavior;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\CommandInterface;
use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;

abstract class AbstractCommandSpec extends ObjectBehavior
{
    /** @var ResourceTranslation|null */
    private static $resourceTranslation;

    public function it_implements_command_interface(): void
    {
        $this->shouldImplement(CommandInterface::class);
    }

    protected static function getSharedResourceTranslation(): ResourceTranslation
    {
        if (null === self::$resourceTranslation) {
            self::$resourceTranslation = new ResourceTranslation('test', 'test', []);
        }

        return self::$resourceTranslation;
    }
}
