<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy\Collector;

use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;

/**
 * Context of vacancy data that has been accepted for persisting and research
 */
class AcceptanceDto
{
    /**
     * Conditions which has been checked to ensure vacancy data should be collected and analysed
     *
     * @var string[]
     */
    private $conditions;

    /**
     * Context of parsed vacancy data
     *
     * @var ParsedDto
     */
    private $data;

    /**
     * Returns conditions which has been checked to ensure vacancy data should be collected
     *
     * @return string[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Sets conditions which has been checked to ensure vacancy data should be collected
     *
     * @param string[] $conditions Conditions which has been checked to ensure vacancy data should be collected
     *
     * @return void
     */
    public function setConditions(array $conditions): void
    {
        $this->conditions = $conditions;
    }

    /**
     * Returns context of parsed vacancy data
     *
     * @return ParsedDto
     */
    public function getData(): ParsedDto
    {
        return $this->data;
    }

    /**
     * Sets context of parsed vacancy data
     *
     * @param ParsedDto $data Context of parsed vacancy data
     *
     * @return void
     */
    public function setData(ParsedDto $data): void
    {
        $this->data = $data;
    }
}
