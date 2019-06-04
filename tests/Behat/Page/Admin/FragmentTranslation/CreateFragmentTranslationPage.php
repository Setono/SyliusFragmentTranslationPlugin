<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusFragmentTranslationPlugin\Behat\Page\Admin\FragmentTranslation;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

class CreateFragmentTranslationPage extends BaseCreatePage
{
    public function specifyLocale($val): void
    {
        $this->getElement('locale')->setValue($val);
    }

    public function specifySearchString($val): void
    {
        $this->getElement('search')->setValue($val);
    }

    public function specifyReplaceString($val): void
    {
        $this->getElement('replace')->setValue($val);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'locale' => '#setono_sylius_fragment_translation_fragment_translation_locale',
            'search' => '#setono_sylius_fragment_translation_fragment_translation_search',
            'replace' => '#setono_sylius_fragment_translation_fragment_translation_replace',
        ]);
    }
}
