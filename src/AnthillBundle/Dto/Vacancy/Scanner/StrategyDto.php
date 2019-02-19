<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy\Scanner;

/**
 * Context of parsing algorithm used by scanner
 */
class StrategyDto
{
    /**
     * Parsing algorithm name
     *
     * @var string
     */
    private $name;

    /**
     * Sets name for parsing algorithm used by scanner
     *
     * @param string $name Parsing algorithm name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns name of parsing algorithm used by scanner
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
