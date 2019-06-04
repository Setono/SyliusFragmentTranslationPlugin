<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterCommandBusPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('setono_sylius_fragment_translation.messenger.command_bus')) {
            return;
        }

        $commandBusId = $container->getParameter('setono_sylius_fragment_translation.messenger.command_bus');

        if (!$container->has($commandBusId)) {
            return;
        }

        $container->setAlias('setono_sylius_fragment_translation.messenger.command_bus', $commandBusId);
    }
}
