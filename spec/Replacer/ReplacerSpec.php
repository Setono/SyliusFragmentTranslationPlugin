<?php

declare(strict_types=1);

namespace spec\Setono\SyliusFragmentTranslationPlugin\Replacer;

use PhpSpec\ObjectBehavior;
use Setono\SyliusFragmentTranslationPlugin\Replacer\Replacer;
use Setono\SyliusFragmentTranslationPlugin\Replacer\Result;

class ReplacerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Replacer::class);
    }

    public function it_replaces_using_regex_case_insensitive(): void
    {
        $result = $this->replace('aaa', '[A]+', 'b', false, true);
        $result->shouldBeAnInstanceOf(Result::class);
        $result->replacementsDone()->shouldReturn(true);
        $result->getReplacements()->shouldReturn(1);
        $result->getReplacedString()->shouldReturn('b');
    }

    public function it_replaces_using_regex_case_sensitive(): void
    {
        $result = $this->replace('aaa', '[A]+', 'b', true, true);
        $result->shouldBeAnInstanceOf(Result::class);
        $result->replacementsDone()->shouldReturn(false);
        $result->getReplacements()->shouldReturn(0);
        $result->getReplacedString()->shouldReturn('aaa');

        $result = $this->replace('aaa', '[a]+', 'b', true, true);
        $result->shouldBeAnInstanceOf(Result::class);
        $result->replacementsDone()->shouldReturn(true);
        $result->getReplacements()->shouldReturn(1);
        $result->getReplacedString()->shouldReturn('b');
    }

    public function it_replaces_case_insensitive(): void
    {
        $result = $this->replace('aaa', 'A', 'b', false, false);
        $result->shouldBeAnInstanceOf(Result::class);
        $result->replacementsDone()->shouldReturn(true);
        $result->getReplacements()->shouldReturn(3);
        $result->getReplacedString()->shouldReturn('bbb');
    }

    public function it_replaces_case_sensitive(): void
    {
        $result = $this->replace('aaa', 'A', 'b', true, false);
        $result->shouldBeAnInstanceOf(Result::class);
        $result->replacementsDone()->shouldReturn(false);
        $result->getReplacements()->shouldReturn(0);
        $result->getReplacedString()->shouldReturn('aaa');

        $result = $this->replace('aaa', 'a', 'b', true, false);
        $result->shouldBeAnInstanceOf(Result::class);
        $result->replacementsDone()->shouldReturn(true);
        $result->getReplacements()->shouldReturn(3);
        $result->getReplacedString()->shouldReturn('bbb');
    }
}
