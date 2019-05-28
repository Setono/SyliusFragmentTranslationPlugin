<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessResourceTranslationHandler
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

    public function __invoke(ProcessResourceTranslation $message): void
    {
        $resourceTranslation = $message->getResourceTranslation();

        /** @var EntityManagerInterface|null $manager */
        $manager = $this->managerRegistry->getManagerForClass($resourceTranslation->getClass());

        if(null === $manager) {
            throw new \RuntimeException(sprintf('No manager registered for class %s', $resourceTranslation->getClass()));
        }

        $qb = $manager->createQueryBuilder();
        $qb->select('o.id') // todo here it is presumed that we have an id field
            ->from($resourceTranslation->getClass(), 'o')
            ->orderBy('o.id')
            ->setMaxResults(1)
        ;

        $offset = 0;
        $limit = 5;

        do {
            $qb->setFirstResult($offset);

            try {
                $id = $qb->getQuery()->getSingleScalarResult();

                $this->messageBus->dispatch(new ProcessResourceTranslationBatch($resourceTranslation, $id, $limit));
            } catch (NoResultException $exception) {
                $id = null;
            }
        } while(null !== $id && $offset += $limit);
    }
}
