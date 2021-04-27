<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface FragmentTranslationInterface extends ResourceInterface
{
    public function getId(): ?int;

    /**
     * The target locale. The source locale is defined in the configuration of the plugin
     *
     * @return string
     */
    public function getLocale(): ?string;

    public function setLocale(string $locale): void;

    /**
     * The string to search for
     */
    public function getSearch(): ?string;

    public function setSearch(string $search): void;

    /**
     * The string to replace with
     */
    public function getReplacement(): ?string;

    public function setReplacement(string $replacement): void;

    public function getPriority(): int;

    public function setPriority(int $priority): void;

    public function isCaseSensitive(): bool;

    public function setCaseSensitive(bool $caseSensitive): void;
}
