<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Menu;

use Knp\Menu\ItemInterface;

/**
 * Anthill bundle menu builder
 */
class Builder
{
    /**
     * Appends bundle-specific items to root of application menu
     *
     * @param ItemInterface $root Root item of application menu tree
     *
     * @return void
     */
    public function appendTo(ItemInterface $root): void
    {
        $root
            ->addChild('homepage', ['route' => 'anthill_vacancy_index'])
            ->setExtra('translation_domain', 'menu')
        ;
    }
}
