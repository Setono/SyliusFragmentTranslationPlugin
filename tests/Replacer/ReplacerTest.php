<?php

namespace Tests\Setono\SyliusFragmentTranslationPlugin\Replacer;

use Setono\SyliusFragmentTranslationPlugin\Replacer\Replacer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Setono\SyliusFragmentTranslationPlugin\Replacer\Replacer
 */
final class ReplacerTest extends TestCase
{
    /**
     * @test
     * @dataProvider getReplacements
     */
    public function it_replaces(string $input, string $search, string $replace, string $expected, bool $caseSensitive): void
    {
        $replacer = new Replacer();
        self::assertSame($expected, (string) $replacer->replace($input, $search, $replace, $caseSensitive));
    }

    public function getReplacements(): array
    {
        return [
            ['Urban Classic Chinos', 'Ur', 'Klock', 'Urban Classic Chinos', true],
            ['Urban Classic Chinos', 'Ur', 'Klock', 'Urban Classic Chinos', false],

            ['Sort Ur', 'Ur', 'Klock', 'Sort Klock', true],
            ['Sort Ur', 'Ur', 'Klock', 'Sort Klock', false],
            ['Sort Ur', 'ur', 'Klock', 'Sort Ur', true],
            ['Sort Ur', 'ur', 'Klock', 'Sort Klock', false],

            ['Ur', 'Ur', 'Klock', 'Klock', true],
            ['Ur', 'Ur', 'Klock', 'Klock', false],
            ['Ur', 'ur', 'Klock', 'Ur', true],
            ['Ur', 'ur', 'Klock', 'Klock', false],
        ];
    }
}
