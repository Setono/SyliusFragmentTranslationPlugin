<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Exception;

use InvalidArgumentException;
use function Safe\sprintf;

final class NoModelClassSetException extends InvalidArgumentException
{
    public function __construct(string $resource, bool $translatable = false)
    {
        parent::__construct(sprintf('No ' . ($translatable ? 'translatable ' : '') . 'model class set for resource %s', $resource));
    }
}
