<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use InvalidArgumentException;
use Sylius\Component\Resource\Model\TranslatableInterface;

final class TranslatableResourceExpectedException extends InvalidArgumentException
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('The class %s must implement %s', $class, TranslatableInterface::class));
    }
}
