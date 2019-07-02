<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface FragmentTranslationInterface extends ResourceInterface
{
    /**
     * The target locale. The source locale is defined in the configuration of the plugin
     *
     * @return string
     */
    public function getLocale(): ?string;

    public function setLocale(string $locale): void;

    /**
     * The string to search for. This can be a regex string
     */
    public function getSearch(): ?string;

    public function setSearch(string $search): void;

    /**
     * The string to replace with. Note that since the search string can be a regex then this string can also contain references
     */
    public function getReplacement(): ?string;

    public function setReplacement(string $replacement): void;

    public function getPriority(): int;

    public function setPriority(int $priority): void;

    public function isCaseSensitive(): bool;

    public function setCaseSensitive(bool $caseSensitive): void;

    public function isRegex(): bool;

    public function setRegex(bool $regex): void;
}
