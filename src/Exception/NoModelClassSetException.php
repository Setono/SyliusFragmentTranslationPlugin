<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Exception;

use InvalidArgumentException;
use Safe\Exceptions\StringsException;
use function Safe\sprintf;

final class NoModelClassSetException extends InvalidArgumentException
{
    /**
     * NoModelClassSetException constructor.
     *
     *
     * @throws StringsException
     */
    public function __construct(string $resource, bool $translatable = false)
    {
        parent::__construct(sprintf('No ' . ($translatable ? 'translatable ' : '') . 'model class set for resource %s', $resource));
    }
}
