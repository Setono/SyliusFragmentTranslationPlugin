<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

final class Result
{
    private string $replacedString;

    private int $replacements;

    public function __construct(string $replacedString, int $replacements)
    {
        $this->replacedString = $replacedString;
        $this->replacements = $replacements;
    }

    public function getReplacedString(): string
    {
        return $this->replacedString;
    }

    public function getReplacements(): int
    {
        return $this->replacements;
    }

    public function replacementsDone(): bool
    {
        return $this->replacements > 0;
    }
}
