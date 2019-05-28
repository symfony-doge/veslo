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

namespace Veslo\AnthillBundle\Event\Vacancy\Decision;

use Symfony\Component\EventDispatcher\Event;
use Veslo\AnthillBundle\Vacancy\DecisionInterface;

/**
 * Describes context of the applied decision for subscribers that can perform some side actions
 */
class AppliedEvent extends Event
{
    /**
     * Event name
     *
     * @const string
     */
    public const NAME = 'veslo.anthill.event.vacancy.decision.applied';

    /**
     * The decision
     *
     * @var DecisionInterface
     */
    private $decision;

    /**
     * Decision context
     *
     * @var object
     */
    private $context;

    /**
     * Evaluation result
     *
     * @var bool
     */
    private $isApplied;

    /**
     * AppliedEvent constructor.
     *
     * @param DecisionInterface $decision  The decision
     * @param object            $context   Decision context
     * @param bool              $isApplied Evaluation result
     */
    public function __construct(DecisionInterface $decision, object $context, bool $isApplied)
    {
        $this->decision  = $decision;
        $this->context   = $context;
        $this->isApplied = $isApplied;
    }

    /**
     * Returns an applied decision
     *
     * @return DecisionInterface
     */
    public function getDecision(): DecisionInterface
    {
        return $this->decision;
    }

    /**
     * Returns a decision context
     *
     * @return object
     */
    public function getContext(): object
    {
        return $this->context;
    }

    /**
     * Returns the evaluation result
     *
     * @return bool
     */
    public function isApplied(): bool
    {
        return $this->isApplied;
    }
}
