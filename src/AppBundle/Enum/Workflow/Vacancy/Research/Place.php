<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Enum\Workflow\Vacancy\Research;

/**
 * Dictionary of places for workflow
 */
final class Place
{
    /**
     * State represent founded vacancy, ready for parsing
     *
     * @const string
     */
    public const FOUND = 'found';

    /**
     * State represent parsed vacancy, ready for collecting
     *
     * @const string
     */
    public const PARSED = 'parsed';

    /**
     * State represent synchronized and collected vacancy, ready for indexing
     *
     * @const string
     */
    public const COLLECTED = 'collected';

    /**
     * State represent indexed vacancy
     *
     * @const string
     */
    public const INDEXED = 'indexed';
}
