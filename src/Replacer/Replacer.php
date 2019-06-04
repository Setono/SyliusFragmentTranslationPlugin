<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Replacer;

use Safe\Exceptions\PcreException;
use function Safe\preg_replace;

final class Replacer implements ReplacerInterface
{
    /**
     * @param string $str
     * @param string $search
     * @param string $replace
     * @param bool $caseSensitive
     * @param bool $regex
     * @return Result
     * @throws PcreException
     */
    public function replace(string $str, string $search, string $replace, bool $caseSensitive, bool $regex): Result
    {
        if ($regex) {
            $str = preg_replace('#' . $search . '#' . ($caseSensitive ? '' : 'i'), $replace, $str, -1, $count);
        } elseif ($caseSensitive) {
            $str = str_replace($search, $replace, $str, $count);
        } else {
            $str = str_ireplace($search, $replace, $str, $count);
        }

        return new Result($str, $count);
    }
}
