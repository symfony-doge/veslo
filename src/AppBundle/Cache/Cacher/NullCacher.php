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

namespace Veslo\AppBundle\Cache\Cacher;

use Veslo\AppBundle\Cache\CacherInterface;

/**
 * Null cacher is a dummy object for cases when a real cacher is not needed
 * Eliminates an extra boilerplate checks in client code
 *
 * @see Nice pattern description here: https://github.com/domnikl/DesignPatternsPHP/tree/master/Behavioral/NullObject
 */
class NullCacher implements CacherInterface
{
    /**
     * {@inheritdoc}
     */
    public function save($value): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate(): bool
    {
        return true;
    }
}
