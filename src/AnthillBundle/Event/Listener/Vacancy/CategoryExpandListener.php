<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019-2021 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\AnthillBundle\Event\Listener\Vacancy;

use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Event\Vacancy\Decision\AppliedEvent;
use Veslo\AnthillBundle\Vacancy\CategoryAssigner;
use Veslo\AnthillBundle\Vacancy\Decision\NotADuplicate as NotADuplicateDecision;

/**
 * Listens decision events for unique-violating vacancies and adds new category relations for them.
 *
 * Example: a vacancy for category 'golang' has found, but it is already exists for the roadmap under 'php' category;
 * a vacancy will receive new 'golang' category then, after being rejected.
 */
class CategoryExpandListener
{
    /**
     * Adds a vacancy to the specified category
     *
     * @var CategoryAssigner
     */
    private $categoryAssigner;

    /**
     * CategoryExpandListener constructor.
     *
     * @param CategoryAssigner $categoryAssigner Adds a vacancy to the specified category
     */
    public function __construct(CategoryAssigner $categoryAssigner)
    {
        $this->categoryAssigner = $categoryAssigner;
    }

    /**
     * Calls a category assigner service to expand the categories list for a given vacancy duplicate
     *
     * @param AppliedEvent $event Describes context of the applied decision
     *
     * @return void
     */
    public function onDecisionApplied(AppliedEvent $event): void
    {
        $decision = $event->getDecision();

        if (!$decision instanceof NotADuplicateDecision) {
            return;
        }

        if ($event->isApplied()) {
            return;
        }

        /** @var ParsedDto $scanResult */
        $scanResult = $event->getContext();

        $this->categoryAssigner->expandByParsedDto($scanResult);
    }
}
