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
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessResourceTranslationHandler implements MessageHandlerInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(ManagerRegistry $managerRegistry, MessageBusInterface $messageBus)
    {
        $this->managerRegistry = $managerRegistry;
        $this->messageBus = $messageBus;
    }

    /**
     * @param ProcessResourceTranslation $message
     *
     * @throws MappingException
     * @throws NonUniqueResultException
     * @throws StringsException
     */
    public function __invoke(ProcessResourceTranslation $message): void
    {
        $resourceTranslation = $message->getResourceTranslation();

        /** @var EntityManagerInterface|null $manager */
        $manager = $this->managerRegistry->getManagerForClass($resourceTranslation->getClass());

        if (null === $manager) {
            throw new RuntimeException(sprintf('No manager registered for class %s', $resourceTranslation->getClass()));
        }

        $metaData = $manager->getClassMetadata($resourceTranslation->getClass());

        $qb = $manager->createQueryBuilder();
        $qb->select('o.' . $metaData->getSingleIdentifierFieldName())
            ->from($resourceTranslation->getClass(), 'o')
            ->orderBy('o.id')
            ->setMaxResults(1)
        ;

        $offset = 0;
        $limit = 100;

        do {
            $qb->setFirstResult($offset);

            try {
                $id = (int) $qb->getQuery()->getSingleScalarResult();

                $this->messageBus->dispatch(new ProcessResourceTranslationBatch($resourceTranslation, $id, $limit));
            } catch (NoResultException $exception) {
                $id = null;
            }

            $offset += $limit;
        } while (null !== $id);
    }
}
