<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Event\Listener;

use Symfony\Component\EventDispatcher\Event;
use Veslo\AnthillBundle\Menu\Builder;
use Veslo\AppBundle\Event\Menu\ConfigureEvent;

/**
 * Bridge between application level menu builder and bundle menu builder
 */
class MenuConfigureListener extends Event
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
