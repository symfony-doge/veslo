<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Scanner;

use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Vacancy\Scanner\StrategyPool\OneToOneStrategyPool;
use Veslo\AnthillBundle\Vacancy\ScannerInterface;

/**
 * Vacancy scanner that supports multiple parsing strategies based on vacancy location
 */
class MultistrategicScanner implements ScannerInterface
{
    /**
     * Establishes relation between vacancy search strategies and suitable parse strategies
     *
     * @var OneToOneStrategyPool
     */
    private $strategyPool;

    /**
     * Parse algorithm chosen for execution
     *
     * @var StrategyInterface
     */
    private $_strategy;

    /**
     * MultistrategicScanner constructor.
     *
     * @param OneToOneStrategyPool $strategyPool Establishes relation between vacancy search and parse strategies
     */
    public function __construct(OneToOneStrategyPool $strategyPool)
    {
        $this->strategyPool = $strategyPool;
        $this->_strategy    = null;
    }

    /**
     * Marks specific parse algorithm for execution based on vacancy search algorithm
     *
     * @param string $roadmapStrategyName Roadmap search strategy name
     *
     * @return void
     */
    public function chooseStrategy(string $roadmapStrategyName): void
    {
        $this->_strategy = $this->strategyPool->choose($roadmapStrategyName);
    }

    /**
     * {@inheritdoc}
     */
    public function scan(string $vacancyUrl): RawDto
    {
        // TODO: Implement scan() method.
        // if (!$this->_strategy instanceof StrategyInterface

        $data = new RawDto();

        return $data;
    }
}
