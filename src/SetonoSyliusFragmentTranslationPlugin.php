<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin;

use Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler\RegisterCommandBusPass;
use Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler\RegisterResourceTranslationsPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SetonoSyliusFragmentTranslationPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterResourceTranslationsPass()); // todo the priority should probably be lower for this to run AFTER all plugins have been registered with their respective resources
        $container->addCompilerPass(new RegisterCommandBusPass());
    }
}
