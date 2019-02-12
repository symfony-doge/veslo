<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Enum\Fixture;

/**
 * Dictionary of common fixture groups
 */
final class Group
{
    /**
     * Tagged fixtures should be safe for execution in production environment
     * This is usually applicable to static data
     *
     * @const string
     */
    public const PRODUCTION = 'prod';
}
