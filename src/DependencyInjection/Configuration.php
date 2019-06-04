<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection;

use function method_exists;
use Setono\SyliusFragmentTranslationPlugin\Form\Type\FragmentTranslationType;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslation;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslationInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_fragment_translation');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('setono_sylius_fragment_translation');
        }

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->scalarNode('locale')
                    ->cannotBeEmpty()
                    ->defaultValue('%locale%')
                    ->info('This is the locale considered the base locale for this plugin. I.e. the source locale for translations')
                    ->example('en_US')
                ->end()
                ->arrayNode('resource_translations')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->fixXmlConfig('property')
                        ->children()
                            ->scalarNode('name')->end()
                            ->arrayNode('properties')
                                ->performNoDeepMerging()
                                ->requiresAtLeastOneElement()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('messenger')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('command_bus')
                            ->defaultValue('messenger.bus.default')
                            ->cannotBeEmpty()
                            ->info('The service id for your command bus')
                            ->example('message_bus')
                        ->end()
                        ->scalarNode('transport')
                            ->defaultNull()
                            ->cannotBeEmpty()
                            ->info('The transport to use for Messenger commands')
                            ->example('amqp')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('fragment_translation')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(FragmentTranslation::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(FragmentTranslationInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(FragmentTranslationType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
