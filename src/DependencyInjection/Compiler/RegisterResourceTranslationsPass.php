<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use Setono\SyliusFragmentTranslationPlugin\Translation\ResourceTranslation;
use Sylius\Component\Resource\Model\TranslationInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class RegisterResourceTranslationsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if(!$container->hasParameter('sylius.resources')) {
            return;
        }

        if (!$container->hasParameter('setono_sylius_fragment_translation.resources')) {
            return;
        }

        if (!$container->has('setono_sylius_fragment_translation.registry.resource_translation')) {
            return;
        }

        $registeredResources = $container->getParameter('sylius.resources');
        $resources = $container->getParameter('setono_sylius_fragment_translation.resources');
        $resourceTranslationRegistry = $container->getDefinition('setono_sylius_fragment_translation.registry.resource_translation');

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($resources as $resource => $resourceData) {
            $properties = $resourceData['properties'];

            if(!isset($registeredResources[$resource])) {
                throw new \InvalidArgumentException(sprintf('The resource %s does not exist', $resource)); // todo better exception
            }

            $model = $registeredResources[$resource]['classes']['model'] ?? null;

            if(null === $model) {
                continue;
            }

            if(!is_a($model, TranslationInterface::class, true)) {
                throw new \InvalidArgumentException(sprintf('The class %s does not implement %s', $model, TranslationInterface::class)); // todo better exception
            }

            $obj = new $model;
            foreach ($properties as $property) {
                if(!$propertyAccessor->isReadable($obj, $property)) {
                    throw new \InvalidArgumentException(sprintf('The property %s on resource %s is not readable', $property, $resource)); // todo better exception
                }
            }

            $serviceId = 'setono_sylius_fragment_translation.resource_translation.' . $resource;

            $container->setDefinition($serviceId, new Definition(ResourceTranslation::class, [
                $resource, $model, $properties
            ]));

            $resourceTranslationRegistry->addMethodCall('register', [new Reference($serviceId)]);
        }
    }
}
