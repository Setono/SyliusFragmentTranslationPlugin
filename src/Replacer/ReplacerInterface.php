<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

interface ReplacerInterface
{
    public function replace(string $str, string $search, string $replace, bool $caseSensitive, bool $regex): Result;
}
