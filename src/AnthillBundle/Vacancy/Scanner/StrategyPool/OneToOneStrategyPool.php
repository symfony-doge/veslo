<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Scanner\StrategyPool;

use Veslo\AnthillBundle\Exception\Vacancy\Scanner\StrategyNotFoundException;
use Veslo\AnthillBundle\Vacancy\Scanner\StrategyInterface;

/**
 * Establishes one-to-one relation between vacancy search strategies and suitable parse strategies
 */
class OneToOneStrategyPool
{
    /**
     * Array of search to parse strategy mappings
     *
     * @var array
     */
    private $strategyMap;

    /**
     * OneToOneStrategyPool constructor.
     *
     * @param array $strategyMap Roadmap search strategy to parse strategy mappings
     */
    public function __construct(array $strategyMap)
    {
        $this->strategyMap = $strategyMap;
    }

    /**
     * Returns parse strategy that supports specified search strategy
     *
     * @param string $searchStrategyName Name of vacancy search algorithm for specific website-provider
     *
     * @return StrategyInterface Parsing strategy
     *
     * @throws StrategyNotFoundException
     */
    public function choose(string $searchStrategyName): StrategyInterface
    {
        if (!array_key_exists($searchStrategyName, $this->strategyMap)) {
            throw StrategyNotFoundException::withSearchStrategyName($searchStrategyName);
        }

        return $this->strategyMap[$searchStrategyName];
    }
}
