<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use InvalidArgumentException;

final class NoModelClassSetException extends InvalidArgumentException
{
    public function __construct(string $resource, bool $translatable = false)
    {
        parent::__construct(sprintf('No ' . ($translatable ? 'translatable ' : '') . 'model class set for resource %s', $resource));
    }
}
