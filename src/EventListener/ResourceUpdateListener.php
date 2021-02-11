<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\EventListener;

use Setono\SyliusFragmentTranslationPlugin\Message\Command\TranslateResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Registry\ResourceTranslationRegistryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class ResourceUpdateListener
{
    private MessageBusInterface $commandBus;

    private ResourceTranslationRegistryInterface $resourceTranslationRegistry;

    public function __construct(MessageBusInterface $commandBus, ResourceTranslationRegistryInterface $resourceTranslationRegistry)
    {
        $this->commandBus = $commandBus;
        $this->resourceTranslationRegistry = $resourceTranslationRegistry;
    }

    public function onEvent(ResourceControllerEvent $event): void
    {
        $resource = $event->getSubject();
        if (null === $resource) {
            return;
        }

        Assert::isInstanceOf($resource, ResourceInterface::class);

        $resourceTranslation = $this->resourceTranslationRegistry->findByClass(get_class($resource));
        if (null === $resourceTranslation) {
            return;
        }

        $this->commandBus->dispatch(new TranslateResourceTranslation($resourceTranslation, (int) $resource->getId()));
    }
}
