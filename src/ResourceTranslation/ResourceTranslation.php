<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\ResourceTranslation;

final class ResourceTranslation
{
    private string $resource;

    private string $class;

    /** @var array<string> */
    private array $properties;

    /**
     * @param array<string> $properties
     */
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

    /**
     * @return array<string>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
