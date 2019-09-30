<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Message\Handler;

use Setono\DoctrineORMBatcher\Query\QueryRebuilderInterface;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslationBatch;
use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessResourceTranslationBatchHandler implements MessageHandlerInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var QueryRebuilderInterface */
    private $queryRebuilder;

    public function __construct(MessageBusInterface $messageBus, QueryRebuilderInterface $queryRebuilder)
    {
        $this->messageBus = $messageBus;
        $this->queryRebuilder = $queryRebuilder;
    }

    public function __invoke(ProcessResourceTranslationBatch $message): void
    {
        $q = $this->queryRebuilder->rebuild($message->getBatch());

        $objects = $q->getResult();

        foreach ($objects as $object) {
            $this->messageBus->dispatch(new TranslateResourceTranslation($message->getResourceTranslation(), $object['id']));
        }
    }
}
