<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Event\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Menu configure event to allow a menu to be extended by different bundles
 */
class ConfigureEvent extends Event
{
    /**
     * Event name
     *
     * @const string
     */
    public const NAME = 'veslo.app.event.menu.configure';

    /**
     * Root item of application menu tree
     *
     * @var ItemInterface
     */
    private $root;

    /**
     * ConfigureEvent constructor.
     *
     * @param ItemInterface $root Root item of application menu tree
     */
    public function __construct(ItemInterface $root)
    {
        $this->root = $root;
    }

    /**
     * Returns a root item of application menu tree
     *
     * @return ItemInterface
     */
    public function getRoot(): ItemInterface
    {
        return $this->root;
    }
}
