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

namespace Veslo\AnthillBundle\Event\Listener;

use Veslo\AnthillBundle\Menu\Builder;
use Veslo\AppBundle\Event\Menu\ConfigureEvent;

/**
 * A bridge between application level menu builder and bundle menu builder
 */
class MenuConfigureListener
{
    /**
     * Bundle-specific menu builder
     *
     * @var Builder
     */
    private $menuBuilder;

    /**
     * MenuConfigureListener constructor.
     *
     * @param Builder $menuBuilder Bundle-specific menu builder
     */
    public function __construct(Builder $menuBuilder)
    {
        $this->menuBuilder = $menuBuilder;
    }

    /**
     * Calls bundle-specific menu builder to append available items to root of application menu
     *
     * @param ConfigureEvent $event Menu configure event to allow a menu to be extended by different bundles
     *
     * @return void
     */
    public function onMenuConfigure(ConfigureEvent $event): void
    {
        $root = $event->getRoot();

        $this->menuBuilder->appendTo($root);
    }
}
