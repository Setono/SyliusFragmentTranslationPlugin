<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin;

use Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler\RegisterResourceTranslationsPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SetonoSyliusFragmentTranslationPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterResourceTranslationsPass()); // todo the priority should probably be lower for this to run AFTER all plugins have been registered with their respective resources
    }
}
