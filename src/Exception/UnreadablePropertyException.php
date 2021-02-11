<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class UnreadablePropertyException extends InvalidArgumentException
{
    public function __construct(string $class, string $property)
    {
        parent::__construct(sprintf('The property %s on class %s is not readable', $property, $class));
    }
}
