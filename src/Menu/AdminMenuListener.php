<?php

declare(strict_types=1);

namespace Setono\SyliusFragmentTranslationPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $catalog = $menu->getChild('catalog');
        if (null !== $catalog) {
            $catalog->addChild('fragment_translations', [
                'route' => 'setono_sylius_fragment_translation_admin_fragment_translation_index',
            ])
                ->setLabel('setono_sylius_fragment_translation.ui.fragment_translations')
                ->setLabelAttribute('icon', 'language')
            ;
        }
    }
}
