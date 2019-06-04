# Sylius Fragment Translation Plugin

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

Will translate fragments of text automatically.

## Installation

### Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this plugin:

```bash
$ composer require setono/sylius-fragment-translation-plugin
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.


### Step 2: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in `config/bundles.php` file of your project before (!) `SyliusGridBundle`:

```php
<?php
$bundles = [
    Setono\SyliusFragmentTranslationPlugin\SetonoSyliusFragmentTranslationPlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
];
```

### Step 3: Configure plugin

First import the general configuration:

```yaml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@SetonoSyliusFragmentTranslationPlugin/Resources/config/app/config.yaml" }
    # ...
```

Then configure what resources you want to 'fragment translate':

```yaml
# config/packages/setono_sylius_fragment_translation.yaml
setono_sylius_fragment_translation:
    resource_translations:
        -
            name: sylius.product
            properties:
                - name
```

### Step 4: Import routing

```yaml
# config/routes/setono_sylius_fragment_translation.yaml
setono_sylius_fragment_translation:
    resource: "@SetonoSyliusFragmentTranslationPlugin/Resources/config/routing.yaml"
```

### Step 5: Update your database schema

```bash
$ php bin/console doctrine:migrations:diff
$ php bin/console doctrine:migrations:migrate
```

## Usage

### Step 1: Create a fragment translation
Go to `/admin/fragment-translations/new` and try to input:

| Field          | Value                                                  |
|----------------|--------------------------------------------------------|
| Locale         | da_DK (or any other secondary locale you have created) |
| Search string  | Sticker                                                |
| Replace string | Klistermærke                                           |


### Step 2: Run CLI command
```bash
$ php bin/console setono:sylius-fragment-translation:translate
```

If you look in your database you should now have Danish translations for the products with names containing `Sticker` and the names should have been translated into `Klistermærke`.

### Step 3: Setup asynchronous Messenger transport (optional, but recommended)
If you have configured the Symfony Messenger component with a asynchronous transport, i.e. `amqp` you can define this transport in the config:

```yaml
# config/packages/setono_sylius_fragment_translation.yaml
setono_sylius_fragment_translation:
    messenger:
        transport: amqp
``` 

[ico-version]: https://poser.pugx.org/setono/sylius-fragment-translation-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-fragment-translation-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-fragment-translation-plugin/license
[ico-travis]: https://travis-ci.com/Setono/SyliusFragmentTranslationPlugin.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusFragmentTranslationPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-fragment-translation-plugin
[link-travis]: https://travis-ci.org/Setono/SyliusFragmentTranslationPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusFragmentTranslationPlugin
