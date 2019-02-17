<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Enum\Workflow\Vacancy\Research;

/**
 * Dictionary of transitions for workflow
 */
final class Transition
{
    /**
     * From found to parsed state, vacancy data will be retrieved from website using URL
     *
     * @const string
     */
    public const TO_PARSE = 'to_parse';

    /**
     * From parsed to collect state, vacancy data will be synchronized with current schema and stored for analysis
     *
     * @const string
     */
    public const TO_COLLECT = 'to_collect';

    /**
     * From collected to indexed state, vacancy will be analysed via sanity criteria and get its rating
     *
     * @const string
     */
    public const TO_INDEX = 'to_index';
}
