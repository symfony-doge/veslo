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

namespace Veslo\AnthillBundle\Vacancy\Decision;

use Veslo\AnthillBundle\Vacancy\DecisionInterface;

/**
 * Will be applied whenever all nested decisions becomes applied
 */
class ChainDecision implements DecisionInterface
{
    /**
     * Nested decisions
     *
     * @var DecisionInterface[]
     */
    private $decisions;

    /**
     * NotADuplicate constructor.
     *
     * @param DecisionInterface[] $decisions Nested decisions
     */
    public function __construct(array $decisions)
    {
        $this->decisions = $decisions;
    }

    /**
     * {@inheritdoc}
     */
    public function isApplied(object $context): bool
    {
        foreach ($this->decisions as $decision) {
            if (!$decision->isApplied($context)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions(): array
    {
        $conditions = [];

        foreach ($this->decisions as $decision) {
            $conditions = array_merge($conditions, $decision->getConditions());
        }

        return $conditions;
    }
}
