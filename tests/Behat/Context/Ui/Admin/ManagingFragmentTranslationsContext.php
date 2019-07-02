<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusFragmentTranslationPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\Setono\SyliusFragmentTranslationPlugin\Behat\Page\Admin\FragmentTranslation\CreateFragmentTranslationPage;
use Tests\Setono\SyliusFragmentTranslationPlugin\Behat\Page\Admin\FragmentTranslation\IndexFragmentTranslationPage;
use Tests\Setono\SyliusFragmentTranslationPlugin\Behat\Page\Admin\FragmentTranslation\UpdateFragmentTranslationPage;
use Webmozart\Assert\Assert;

final class ManagingFragmentTranslationsContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $fragmentTranslationRepository;

    /**
     * @var IndexFragmentTranslationPage
     */
    private $indexPage;

    /**
     * @var CreateFragmentTranslationPage
     */
    private $createPage;

    /**
     * @var UpdateFragmentTranslationPage
     */
    private $updatePage;

    public function __construct(
        RepositoryInterface $fragmentTranslationRepository,
        IndexFragmentTranslationPage $indexPage,
        CreateFragmentTranslationPage $createPage,
        UpdateFragmentTranslationPage $updatePage
    ) {
        $this->fragmentTranslationRepository = $fragmentTranslationRepository;
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @Given I want to create a new fragment translation
     */
    public function iWantToCreateANewFragmentTranslation(): void
    {
        $this->createPage->open();
    }

    /**
     * @When I fill the locale with :locale
     */
    public function iFillTheLocale($locale): void
    {
        $this->createPage->specifyLocale($locale);
    }

    /**
     * @When I fill the search string with :search
     */
    public function iFillTheSearchString($search): void
    {
        $this->createPage->specifySearchString($search);
    }

    /**
     * @When I fill the replacement with :replacement
     */
    public function iFillTheReplaceString($replacement): void
    {
        $this->createPage->specifyReplacement($replacement);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @Then the fragment translation with locale :locale, search :search, and replacement :replacement should appear in the store
     */
    public function theFragmentTranslationShouldAppearInTheStore($locale, $search, $replacement): void
    {
        $this->indexPage->open();

        Assert::true(
            $this->indexPage->isSingleResourceOnPage([
                'locale' => $locale,
                'search' => $search,
                'replacement' => $replacement,
            ]),
            sprintf('Fragment translation (locale: %s, search: %s, replacement: %s) should exist but it does not', $locale, $search, $replacement)
        );
    }

    /**
     * @Given I want to update the fragment translation with locale :locale, search :search, and replacement :replacement
     */
    public function iWantToUpdateTheFragmentTranslation($locale, $search, $replacement): void
    {
        /** @var ResourceInterface $fragmentTranslation */
        $fragmentTranslation = $this->fragmentTranslationRepository->findOneBy([
            'locale' => $locale,
            'search' => $search,
            'replacement' => $replacement
        ]);

        $this->updatePage->open([
            'id' => $fragmentTranslation->getId(),
        ]);
    }

    /**
     * @When I update the fragment translation with search :search
     */
    public function iUpdateTheFragmentTranslation($search): void
    {
        $this->updatePage->specifySearchString($search);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges(): void
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then this fragment translation's search string should be :search
     */
    public function thisFragmentTranslationsSearchShouldBe($search): void
    {
        Assert::eq($search, $this->updatePage->getSearch());
    }
}
