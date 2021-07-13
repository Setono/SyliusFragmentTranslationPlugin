<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusFragmentTranslationPlugin\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Setono\SyliusFragmentTranslationPlugin\DependencyInjection\Configuration;
use Setono\SyliusFragmentTranslationPlugin\Form\Type\FragmentTranslationType;
use Setono\SyliusFragmentTranslationPlugin\Model\FragmentTranslation;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function values_are_invalid_if_required_value_is_not_provided(): void
    {
        $this->assertConfigurationIsInvalid(
            [[]],
            'The child node "resource_translations" at path "setono_sylius_fragment_translation" must be configured.' // (part of) the expected exception message - optional
        );
    }

    /**
     * @test
     */
    public function processed_value_contains_required_value(): void
    {
        $this->assertProcessedConfigurationEquals([
            [
                'resource_translations' => [
                    ['name' => 'sylius.product', 'properties' => ['name']]
                ]
            ],
            [
                'resource_translations' => [
                    ['name' => 'sylius.product', 'properties' => ['name', 'description']]
                ]
            ],
        ], [
            'resource_translations' => [
                'sylius.product' => ['properties' => ['name', 'description']]
            ],
            'driver' => SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            'locale' => '%locale%',
            'resources' => [
                'fragment_translation' => [
                    'classes' => [
                        'model' => FragmentTranslation::class,
                        'controller' => ResourceController::class,
                        'factory' => Factory::class,
                        'form' => FragmentTranslationType::class,
                    ]
                ]
            ]
        ]);
    }
}
