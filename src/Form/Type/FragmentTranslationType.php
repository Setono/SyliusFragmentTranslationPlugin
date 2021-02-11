<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class FragmentTranslationType extends AbstractResourceType
{
    private RepositoryInterface $localeRepository;

    private string $sourceLocale;

    /**
     * @param array<array-key, string> $validationGroups
     */
    public function __construct(
        string $dataClass,
        RepositoryInterface $localeRepository,
        string $sourceLocale,
        array $validationGroups = []
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->localeRepository = $localeRepository;
        $this->sourceLocale = $sourceLocale;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // todo make this better

        /** @var array<string, string> $locales */
        $locales = [];
        $collection = $this->localeRepository->findAll();

        /** @var LocaleInterface $item */
        foreach ($collection as $item) {
            $code = $item->getCode();
            if (null === $code || $code === $this->sourceLocale) {
                continue;
            }

            $locales[$code] = $code;
        }

        $builder
            ->add('sourceLocale', TextType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.source_locale',
                'disabled' => true,
                'data' => $this->sourceLocale,
            ])
            ->add('locale', ChoiceType::class, [
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.locale',
                'choices' => $locales,
            ])
            ->add('search', TextType::class, [
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.search',
            ])
            ->add('replacement', TextType::class, [
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.replacement',
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.priority',
            ])
            ->add('caseSensitive', CheckboxType::class, [
                'required' => false,
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.case_sensitive',
            ])
            ->add('regex', CheckboxType::class, [
                'required' => false,
                'label' => 'setono_sylius_fragment_translation.form.fragment_translation.regex',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_fragment_translation_fragment_translation';
    }
}
