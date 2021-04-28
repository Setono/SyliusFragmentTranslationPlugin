<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

final class Replacer implements ReplacerInterface
{
    public function replace(string $input, string $search, string $replace, bool $caseSensitive): Result
    {
        $flags = '';
        if (!$caseSensitive) {
            $flags .= 'i';
        }

        $delimiter = '#';

        $search = preg_quote($search, $delimiter);

        $output = preg_replace(sprintf('%s\b%s\b%s%s', $delimiter, $search, $delimiter, $flags), $replace, $input);

        return new Result($input, $output);
    }
}
