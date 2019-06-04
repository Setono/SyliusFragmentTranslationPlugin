<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use RuntimeException;
use Safe\Exceptions\StringsException;
use function Safe\sprintf;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslationInterface;
use Setono\SyliusFragmentTranslationPlugin\Replacer\ReplacerInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class TranslateResourceTranslationHandler implements MessageHandlerInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var RepositoryInterface
     */
    private $fragmentTranslationRepository;

    /**
     * @var ReplacerInterface
     */
    private $replacer;

    /**
     * @var string
     */
    private $baseLocale;

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

    /**
     * @param TranslateResourceTranslation $message
     *
     * @throws MappingException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws StringsException
     */
    public function __invoke(TranslateResourceTranslation $message): void
    {
        $resourceTranslation = $message->getResourceTranslation();

        /** @var EntityManagerInterface|null $manager */
        $manager = $this->managerRegistry->getManagerForClass($resourceTranslation->getClass());

        if (null === $manager) {
            throw new RuntimeException(sprintf('No manager registered for class %s', $resourceTranslation->getClass()));
        }

        $metaData = $manager->getClassMetadata($resourceTranslation->getClass());

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
            ->andWhere('o.' . $metaData->getSingleIdentifierFieldName() . ' = :id')
            ->setParameter('id', $message->getId())
        ;

        /** @var TranslatableInterface $obj */
        $obj = $qb->getQuery()->getSingleResult();

        /** @var TranslationInterface[] $targets */
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
            $translation = $targets[$fragmentTranslation->getLocale()] ?? null;
            if (null === $translation) {
                /** @var TranslationInterface $translation */
                $translation = clone $source;
                $translation->setLocale($fragmentTranslation->getLocale());
                $targets[$fragmentTranslation->getLocale()] = $translation;
            }

            foreach ($resourceTranslation->getProperties() as $property) {
                $val = $propertyAccessor->getValue($source, $property);

                $replacementResult = $this->replacer->replace(
                    $val,
                    $fragmentTranslation->getSearch(),
                    $fragmentTranslation->getReplace(),
                    $fragmentTranslation->isCaseSensitive(),
                    $fragmentTranslation->isRegex()
                );

                if ($replacementResult->replacementsDone()) {
                    $propertyAccessor->setValue($translation, $property, $replacementResult->getReplacedString());

                    $obj->addTranslation($translation);
                }
            }
        }

        $manager->flush();
    }
}
