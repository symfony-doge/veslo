<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Scanner\Strategy\HeadHunter\Api;

use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Vacancy\Scanner\StrategyInterface;

/**
 * Represents vacancy parsing algorithm for HeadHunter site based on public API
 */
class Version20190213 implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetch(string $vacancyUrl): string
    {
        // TODO: Implement fetch() method.
    }

    /**
     * {@inheritdoc}
     */
    public function tokenize(string $data): RawDto
    {
        // TODO: Implement tokenize() method.
    }
}
