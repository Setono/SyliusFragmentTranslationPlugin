<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use Safe\Exceptions\StringsException;
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

final class RegisterResourceTranslationsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws StringsException
     */
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
        $resourceTranslations = $container->getParameter('setono_sylius_fragment_translation.resource_translations');
        $resourceTranslationRegistry = $container->getDefinition('setono_sylius_fragment_translation.registry.resource_translation');

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($resourceTranslations as $resource => $resourceTranslation) {
            $properties = $resourceTranslation['properties'];

            if (!isset($registeredResources[$resource])) {
                throw new ResourceNotFoundException($resource);
            }

            /** @var string|null $model */
            $model = $registeredResources[$resource]['classes']['model'] ?? null;

            if (null === $model) {
                throw new NoModelClassSetException($resource);
            }

            if (!is_a($model, TranslatableInterface::class, true)) {
                throw new TranslatableResourceExpectedException($model);
            }

            /** @var string|null $translationModel */
            $translationModel = $registeredResources[$resource]['translation']['classes']['model'] ?? null;

            if (null === $translationModel) {
                throw new NoModelClassSetException($resource, true);
            }

            $obj = new $translationModel();
            foreach ($properties as $property) {
                if (!$propertyAccessor->isReadable($obj, $property)) {
                    throw new UnreadablePropertyException($translationModel, $property);
                }
            }

            $serviceId = 'setono_sylius_fragment_translation.resource_translation.' . $resource;

            $container->setDefinition($serviceId, new Definition(ResourceTranslation::class, [
                $resource, $model, $properties,
            ]));

            $resourceTranslationRegistry->addMethodCall('register', [new Reference($serviceId)]);
        }
    }
}
