<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Workflow\Vacancy\Research\Conveyor;

/**
 * Data structure for workflow conveyor
 */
class Payload
{
    /**
     * Data to be passed through workflow
     *
     * @var object
     */
    private $data;

    /**
     * Payload constructor.
     *
     * @param object $data Data to be passed through workflow
     */
    public function __construct(object $data)
    {
        $this->data = $data;
    }

    /**
     * Returns data to be passed through workflow
     *
     * @return object
     */
    public function getData(): object
    {
        return $this->data;
    }
}
