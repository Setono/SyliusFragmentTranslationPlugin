<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use RuntimeException;
use Safe\Exceptions\StringsException;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use function Safe\sprintf;

final class ProcessResourceTranslationBatchHandler implements MessageHandlerInterface
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
     * @param ProcessResourceTranslationBatch $message
     * @throws MappingException
     * @throws StringsException
     */
    public function __invoke(ProcessResourceTranslationBatch $message): void
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
            ->andWhere('o.id >= :offset')
            ->orderBy('o.id')
            ->setMaxResults($message->getLimit())
            ->setParameter('offset', $message->getOffsetId())
        ;

        $objects = $qb->getQuery()->getResult();

        foreach ($objects as $object) {
            $this->messageBus->dispatch(new TranslateResourceTranslation($resourceTranslation, $object['id']));
        }
    }
}
