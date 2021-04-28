<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

final class Result
{
    private string $input;

    private string $output;

    private bool $replacementsDone;

    public function __construct(string $input, string $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->replacementsDone = $input !== $output;
    }

    /**
     * The string before any replacements was done
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * The string after any replacements was done
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    public function __toString(): string
    {
        return $this->getOutput();
    }

    public function wasReplacementsDone(): bool
    {
        return $this->replacementsDone;
    }
}
