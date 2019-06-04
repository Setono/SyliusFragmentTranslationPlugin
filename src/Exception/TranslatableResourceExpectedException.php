<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Exception;

use InvalidArgumentException;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Safe\Exceptions\StringsException;
use function Safe\sprintf;

final class TranslatableResourceExpectedException extends InvalidArgumentException
{
    /**
     * TranslatableResourceExpectedException constructor.
     * @param string $class
     * @throws StringsException
     */
    public function __construct(string $class)
    {
        parent::__construct(sprintf('The class %s must implement %s', $class, TranslatableInterface::class));
    }
}
