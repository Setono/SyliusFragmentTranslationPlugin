<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Setono\DoctrineORMBatcher\Query\QueryRebuilderInterface;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class ProcessResourceTranslationBatchHandler implements MessageHandlerInterface
{
    private MessageBusInterface $messageBus;

    private QueryRebuilderInterface $queryRebuilder;

    public function __construct(MessageBusInterface $messageBus, QueryRebuilderInterface $queryRebuilder)
    {
        $this->messageBus = $messageBus;
        $this->queryRebuilder = $queryRebuilder;
    }

    public function __invoke(ProcessResourceTranslationBatch $message): void
    {
        $q = $this->queryRebuilder->rebuild($message->getBatch());

        $objects = $q->getResult();
        Assert::isArray($objects);

        foreach ($objects as $object) {
            Assert::isArray($object);

            /** @var string|int|null $objectId */
            $objectId = $object['id'] ?? null;

            if (null === $objectId) {
                continue;
            }

            Assert::integerish($objectId);

            $this->messageBus->dispatch(
                new TranslateResourceTranslation($message->getResourceTranslation(), (int) $object['id'])
            );
        }
    }
}
