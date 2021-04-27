<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

use Webmozart\Assert\Assert;

final class Replacer implements ReplacerInterface
{
    public function replace(string $str, string $search, string $replace, bool $caseSensitive): Result
    {
        if ($caseSensitive) {
            $str = str_replace($search, $replace, $str, $count);
        } else {
            $str = str_ireplace($search, $replace, $str, $count);
        }

        Assert::integer($count);

        return new Result($str, $count);
    }
}
