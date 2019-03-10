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

namespace Veslo\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Veslo\AppBundle\Event\Menu\ConfigureEvent;

/**
 * Application menu builder
 */
class Builder
{
    /**
     * Factory to create items
     *
     * @var FactoryInterface
     */
    private $menuFactory;

    /**
     * The central point of event listener system
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface         $menuFactory     Factory to create items
     * @param EventDispatcherInterface $eventDispatcher The central point of event listener system
     */
    public function __construct(FactoryInterface $menuFactory, EventDispatcherInterface $eventDispatcher)
    {
        $this->menuFactory     = $menuFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Returns root item for application menu tree rendering
     *
     * @return ItemInterface
     */
    public function createMainMenu(): ItemInterface
    {
        $root = $this->menuFactory->createItem('root');

        $this->eventDispatcher->dispatch(ConfigureEvent::NAME, new ConfigureEvent($root));

        return $root;
    }
}
