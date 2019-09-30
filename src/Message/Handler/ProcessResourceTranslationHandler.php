<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Safe\Exceptions\StringsException;
use function Safe\sprintf;
use Setono\DoctrineORMBatcher\Factory\BatcherFactoryInterface;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessResourceTranslationHandler implements MessageHandlerInterface
{
    /** @var ManagerRegistry */
    private $managerRegistry;

    /** @var MessageBusInterface */
    private $messageBus;

    /** @var BatcherFactoryInterface */
    private $batcherFactory;

    public function __construct(ManagerRegistry $managerRegistry, MessageBusInterface $messageBus, BatcherFactoryInterface $batcherFactory)
    {
        $this->managerRegistry = $managerRegistry;
        $this->messageBus = $messageBus;
        $this->batcherFactory = $batcherFactory;
    }

    /**
     * @throws StringsException
     */
    public function __invoke(ProcessResourceTranslation $message): void
    {
        $resourceTranslation = $message->getResourceTranslation();

        $manager = $this->getManager($resourceTranslation->getClass());

        $qb = $manager->createQueryBuilder();
        $qb->select('o.id')
            ->from($resourceTranslation->getClass(), 'o')
        ;

        $batcherFactory = $this->batcherFactory->createIdCollectionBatcher($qb);
        $batches = $batcherFactory->getBatches();

        foreach ($batches as $batch) {
            $this->messageBus->dispatch(new ProcessResourceTranslationBatch($resourceTranslation, $batch));
        }
    }

    /**
     * @throws StringsException
     */
    private function getManager(string $class): EntityManagerInterface
    {
        /** @var EntityManagerInterface|null $manager */
        $manager = $this->managerRegistry->getManagerForClass($class);

        if (null === $manager) {
            throw new RuntimeException(sprintf('No manager registered for class %s', $class));
        }

        return $manager;
    }
}
