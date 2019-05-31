<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\AnthillBundle\Menu;

use Knp\Menu\ItemInterface;
use Veslo\AnthillBundle\Enum\Route;

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
        $root->addChild(
            'homepage',
            [
                'route'  => 'anthill_vacancy_index',
                'extras' => [
                    'translation_domain' => 'menu',
                ],
            ]
        );

        $root->addChild(
            'archive',
            [
                'route'  => Route::VACANCY_ARCHIVE,
                'extras' => [
                    'routes'             => [
                        ['route' => Route::VACANCY_ARCHIVE_PAGE],
                    ],
                    'translation_domain' => 'menu',
                ],
            ]
        );
    }
}
