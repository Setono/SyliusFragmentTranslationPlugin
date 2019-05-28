<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Translation;

final class ResourceTranslation
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $properties;

    public function __construct(string $resource, string $class, array $properties)
    {
        $this->resource = $resource;
        $this->class = $class;
        $this->properties = $properties;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
