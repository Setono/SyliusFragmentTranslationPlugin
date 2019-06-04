<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection;

use Setono\SyliusFragmentTranslationPlugin\Message\Command\CommandInterface;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusFragmentTranslationExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_fragment_translation.locale', $config['locale']);
        $container->setParameter('setono_sylius_fragment_translation.resource_translations', $config['resource_translations']);
        $container->setParameter('setono_sylius_fragment_translation.messenger.transport', $config['messenger']['transport']);
        $container->setParameter('setono_sylius_fragment_translation.messenger.command_bus', $config['messenger']['command_bus']);

        $loader->load('services.xml');

        $this->registerResources('setono_sylius_fragment_translation', $config['driver'], $config['resources'], $container);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $container->getExtensionConfig($this->getAlias()));

        $transport = $config['messenger']['transport'];

        if (null === $transport) {
            return;
        }

        $container->prependExtensionConfig('framework', [
            'messenger' => [
                'routing' => [
                    CommandInterface::class => $transport,
                ],
            ],
        ]);
    }
}
