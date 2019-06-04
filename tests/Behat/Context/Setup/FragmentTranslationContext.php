<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusFragmentTranslationPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslationInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class FragmentTranslationContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $fragmentTranslationRepository;

    /**
     * @var FactoryInterface
     */
    private $fragmentTranslationFactory;

    public function __construct(RepositoryInterface $fragmentTranslationRepository, FactoryInterface $fragmentTranslationFactory)
    {
        $this->fragmentTranslationRepository = $fragmentTranslationRepository;
        $this->fragmentTranslationFactory = $fragmentTranslationFactory;
    }

    /**
     * @Given the store has a fragment translation with locale :locale, search :search, and replace :replace
     */
    public function theStoreHasAFragmentTranslation($locale, $search, $replace): void
    {
        $obj = $this->create($locale, $search, $replace);

        $this->save($obj);
    }

    private function create(string $locale, string $search, string $replace): FragmentTranslationInterface
    {
        /** @var FragmentTranslationInterface $obj */
        $obj = $this->fragmentTranslationFactory->createNew();

        $obj->setLocale($locale);
        $obj->setSearch($search);
        $obj->setReplace($replace);

        return $obj;
    }

    private function save(FragmentTranslationInterface $obj): void
    {
        $this->fragmentTranslationRepository->add($obj);
    }
}
