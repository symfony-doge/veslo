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

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AppBundle\Workflow\Vacancy\PitInterface;
use Veslo\AppBundle\Workflow\Vacancy\Research\Conveyor;

/**
 * This pit is full of dung (vacancies, especially for PHP engineers).
 */
class DungPit implements PitInterface
{
    /**
     * Manages data exchange between workers using workflow
     *
     * @var Conveyor
     */
    private $conveyor;

    /**
     * Dto class
     *
     * @var string
     */
    private $dtoClass;

    /**
     * DungPit constructor.
     *
     * @param Conveyor $conveyor Manages data exchange between workers using workflow
     * @param string   $dtoClass
     */
    public function __construct(Conveyor $conveyor, string $dtoClass)
    {
        $this->conveyor = $conveyor;
        $this->dtoClass = $dtoClass;
    }

    /**
     * {@inheritdoc}
     */
    public function poll(): ?object
    {
        return $this->conveyor->receive($this->dtoClass);
    }

    /**
     * {@inheritdoc}
     */
    public function offer(object $dto): bool
    {
        $this->conveyor->send($dto);

        // No overflow checks.
        return true;
    }
}
