<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use Setono\SyliusFragmentTranslationPlugin\EventListener\ResourceUpdateListener;
use Setono\SyliusFragmentTranslationPlugin\Exception\NoModelClassSetException;
use Setono\SyliusFragmentTranslationPlugin\Exception\ResourceNotFoundException;
use Setono\SyliusFragmentTranslationPlugin\Exception\TranslatableResourceExpectedException;
use Setono\SyliusFragmentTranslationPlugin\Exception\UnreadablePropertyException;
use Setono\SyliusFragmentTranslationPlugin\ResourceTranslation\ResourceTranslation;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

final class RegisterResourceTranslationsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('sylius.resources')) {
            return;
        }

        if (!$container->hasParameter('setono_sylius_fragment_translation.resource_translations')) {
            return;
        }

        if (!$container->hasDefinition('setono_sylius_fragment_translation.registry.resource_translation')) {
            return;
        }

        $registeredResources = $container->getParameter('sylius.resources');
        Assert::isArray($registeredResources);

        $resourceTranslations = $container->getParameter('setono_sylius_fragment_translation.resource_translations');
        Assert::isArray($resourceTranslations);

        $resourceTranslationRegistry = $container->getDefinition('setono_sylius_fragment_translation.registry.resource_translation');

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        /**
         * @var string $resource
         * @var array $resourceTranslation
         */
        foreach ($resourceTranslations as $resource => $resourceTranslation) {
            Assert::keyExists($resourceTranslation, 'properties');

            $properties = $resourceTranslation['properties'];
            Assert::isArray($properties);

            if (!isset($registeredResources[$resource])) {
                throw new ResourceNotFoundException($resource);
            }

            /** @var class-string|null $model */
            $model = $registeredResources[$resource]['classes']['model'] ?? null;

            if (null === $model) {
                throw new NoModelClassSetException($resource);
            }

            if (!is_a($model, TranslatableInterface::class, true)) {
                throw new TranslatableResourceExpectedException($model);
            }

            /** @var class-string|null $translationModel */
            $translationModel = $registeredResources[$resource]['translation']['classes']['model'] ?? null;

            if (null === $translationModel) {
                throw new NoModelClassSetException($resource, true);
            }

            $obj = new $translationModel();
            foreach ($properties as $property) {
                Assert::string($property);

                if (!$propertyAccessor->isReadable($obj, $property)) {
                    throw new UnreadablePropertyException($translationModel, $property);
                }
            }

            $serviceId = 'setono_sylius_fragment_translation.resource_translation.' . $resource;

            $container->setDefinition($serviceId, new Definition(ResourceTranslation::class, [
                $resource, $model, $properties,
            ]));

            $resourceTranslationRegistry->addMethodCall('register', [new Reference($serviceId)]);

            $this->registerEventListener($container, $resource);
        }
    }

    private function registerEventListener(ContainerBuilder $container, string $resource): void
    {
        $definition = new Definition(ResourceUpdateListener::class, [
            new Reference('setono_sylius_fragment_translation.command_bus'),
            new Reference('setono_sylius_fragment_translation.registry.resource_translation'),
        ]);
        $definition->addTag('kernel.event_listener', ['event' => $resource . '.post_create', 'method' => 'onEvent']);
        $definition->addTag('kernel.event_listener', ['event' => $resource . '.post_update', 'method' => 'onEvent']);

        $container->setDefinition('setono_sylius_fragment_translation.event_listener.resource_update.' . $resource, $definition);
    }
}
