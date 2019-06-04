<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Compiler\RegisterResourceTranslationsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterResourceTranslationsPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterResourceTranslationsPass());
    }
}
