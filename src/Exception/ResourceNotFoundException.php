<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use InvalidArgumentException;

final class ResourceNotFoundException extends InvalidArgumentException
{
    public function __construct(string $resource)
    {
        parent::__construct(sprintf('The resource %s was not found in the list of registered resources', $resource));
    }
}
