<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

use function Safe\preg_replace;
use Webmozart\Assert\Assert;

final class Replacer implements ReplacerInterface
{
    public function replace(string $str, string $search, string $replace, bool $caseSensitive, bool $regex): Result
    {
        if ($regex) {
            $str = preg_replace('#' . $search . '#' . ($caseSensitive ? '' : 'i'), $replace, $str, -1, $count);
        } elseif ($caseSensitive) {
            $str = str_replace($search, $replace, $str, $count);
        } else {
            $str = str_ireplace($search, $replace, $str, $count);
        }

        Assert::string($str);
        Assert::integer($count);

        return new Result($str, $count);
    }
}
