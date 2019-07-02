<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Model;

class FragmentTranslation implements FragmentTranslationInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $locale;

    /** @var string */
    protected $search;

    /** @var string */
    protected $replacement;

    /** @var int */
    protected $priority = 0;

    /** @var bool */
    protected $caseSensitive = false;

    /** @var bool */
    protected $regex = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    public function getReplacement(): ?string
    {
        return $this->replacement;
    }

    public function setReplacement(string $replacement): void
    {
        $this->replacement = $replacement;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    public function setCaseSensitive(bool $caseSensitive): void
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function isRegex(): bool
    {
        return $this->regex;
    }

    public function setRegex(bool $regex): void
    {
        $this->regex = $regex;
    }
}
