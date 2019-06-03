<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use RuntimeException;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslationInterface;
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
     * @var string
     */
    private $baseLocale;

    public function __construct(ManagerRegistry $managerRegistry, RepositoryInterface $fragmentTranslationRepository, string $baseLocale)
    {
        $this->managerRegistry = $managerRegistry;
        $this->fragmentTranslationRepository = $fragmentTranslationRepository;
        $this->baseLocale = $baseLocale;
    }

    /**
     * @param TranslateResourceTranslation $message
     *
     * @throws MappingException
     * @throws NoResultException
     * @throws NonUniqueResultException
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
        $fragmentTranslations = $this->fragmentTranslationRepository->findAll();

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
                continue;
            }

            foreach ($resourceTranslation->getProperties() as $property) {
                $val = $propertyAccessor->getValue($source, $property);

                if ($fragmentTranslation->isRegex()) {
                    $val = preg_replace('#' . $fragmentTranslation->getSearch() . '#' . ($fragmentTranslation->isCaseSensitive() ? '' : 'i'), $fragmentTranslation->getReplace(), $val, -1, $count);
                } elseif ($fragmentTranslation->isCaseSensitive()) {
                    $val = str_replace($fragmentTranslation->getSearch(), $fragmentTranslation->getReplace(), $val, $count);
                } else {
                    $val = str_ireplace($fragmentTranslation->getSearch(), $fragmentTranslation->getReplace(), $val, $count);
                }

                if ($count > 0) {
                    $propertyAccessor->setValue($translation, $property, $val);
                }
            }
        }

        $manager->flush();
    }
}
