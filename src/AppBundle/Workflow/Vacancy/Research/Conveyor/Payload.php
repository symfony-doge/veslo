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
