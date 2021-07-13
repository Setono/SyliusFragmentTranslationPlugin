<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Exception;

use InvalidArgumentException;
use function sprintf;

final class ResourceNotFoundException extends InvalidArgumentException
{
    public function __construct(string $resource)
    {
        parent::__construct(sprintf('The resource %s was not found in the list of registered resources', $resource));
    }
}
