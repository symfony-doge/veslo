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

namespace Veslo\AppBundle\Workflow\Vacancy\Research;

use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AppBundle\Enum\Workflow\Vacancy\Research\Place;
use Veslo\AppBundle\Workflow\Vacancy\Research\Conveyor\Payload;

/**
 * Converts the Marking into something understandable by the subject and vice versa
 */
class MarkingStore implements MarkingStoreInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Payload $subject Data structure for processing in workflow
     */
    public function getMarking($subject)
    {
        $dto = $subject->getData();

        $representation = [];

        if ($dto instanceof LocationDto) {
            $representation[Place::FOUND] = null;
        } elseif ($dto instanceof ParsedDto) {
            $representation[Place::PARSED] = null;
        } else {
            $representation[Place::COLLECTED] = null;
        }

        return new Marking($representation);
    }

    /**
     * {@inheritdoc}
     */
    public function setMarking($subject, Marking $marking)
    {
    }
}
