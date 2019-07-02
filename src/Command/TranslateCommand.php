<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Command;

use Setono\SyliusFragmentTranslationPlugin\Message\Command\ProcessResourceTranslation;
use Setono\SyliusFragmentTranslationPlugin\Registry\ResourceTranslationRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class TranslateCommand extends Command
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var ResourceTranslationRegistryInterface */
    private $resourceTranslationRegistry;

    public function __construct(MessageBusInterface $messageBus, ResourceTranslationRegistryInterface $resourceTranslationRegistry)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
        $this->resourceTranslationRegistry = $resourceTranslationRegistry;
    }

    protected function configure(): void
    {
        $this
            ->setName('setono:sylius-fragment-translation:translate')
            ->setDescription('Translates everything you have configured')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->resourceTranslationRegistry->all() as $resourceTranslation) {
            $this->messageBus->dispatch(new ProcessResourceTranslation($resourceTranslation));
        }

        return 0;
    }
}
