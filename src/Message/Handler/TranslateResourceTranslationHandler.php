<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use function Safe\sprintf;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslationInterface;
use Setono\SyliusFragmentTranslationPlugin\Replacer\ReplacerInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

final class TranslateResourceTranslationHandler implements MessageHandlerInterface
{
    private ManagerRegistry $managerRegistry;

    private RepositoryInterface $fragmentTranslationRepository;

    private ReplacerInterface $replacer;

    private string $baseLocale;

    public function __construct(
        ManagerRegistry $managerRegistry,
        RepositoryInterface $fragmentTranslationRepository,
        ReplacerInterface $replacer,
        string $baseLocale
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->fragmentTranslationRepository = $fragmentTranslationRepository;
        $this->replacer = $replacer;
        $this->baseLocale = $baseLocale;
    }

    public function __invoke(TranslateResourceTranslation $message): void
    {
        $resourceTranslation = $message->getResourceTranslation();

        /** @var EntityManagerInterface|null $manager */
        $manager = $this->managerRegistry->getManagerForClass($resourceTranslation->getClass());

        if (null === $manager) {
            throw new RuntimeException(sprintf('No manager registered for class %s', $resourceTranslation->getClass()));
        }

        /** @var FragmentTranslationInterface[] $fragmentTranslations */
        $fragmentTranslations = $this->fragmentTranslationRepository->findBy([], [
            'priority' => 'desc',
        ]);

        if (count($fragmentTranslations) === 0) {
            return;
        }

        $qb = $manager->createQueryBuilder();
        $qb->select('o')
            ->from($resourceTranslation->getClass(), 'o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $message->getId())
        ;

        /** @var TranslatableInterface $obj */
        $obj = $qb->getQuery()->getSingleResult();

        /** @var array<string, TranslationInterface> $targets */
        $targets = [];
        $source = null;

        foreach ($obj->getTranslations() as $translation) {
            $locale = $translation->getLocale();
            if (null === $locale) {
                continue;
            }

            if ($locale === $this->baseLocale) {
                $source = $translation;
            } else {
                $targets[$locale] = $translation;
            }
        }

        if (null === $source) {
            return; // todo should this emit an error of some sort?
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($fragmentTranslations as $fragmentTranslation) {
            $translation = $targets[(string) $fragmentTranslation->getLocale()] ?? null;
            if (null === $translation) {
                $translation = clone $source;
                $translation->setLocale($fragmentTranslation->getLocale());
                $targets[(string) $fragmentTranslation->getLocale()] = $translation;
            }

            foreach ($resourceTranslation->getProperties() as $property) {
                /** @var mixed $val */
                $val = $propertyAccessor->getValue($source, $property);
                Assert::string($val);

                $replacementResult = $this->replacer->replace(
                    $val,
                    (string) $fragmentTranslation->getSearch(),
                    (string) $fragmentTranslation->getReplacement(),
                    $fragmentTranslation->isCaseSensitive()
                );

                if ($replacementResult->wasReplacementsDone()) {
                    $propertyAccessor->setValue(
                        $translation, $property, $replacementResult->getOutput()
                    );

                    $obj->addTranslation($translation);
                }
            }
        }

        $manager->flush();
    }
}
