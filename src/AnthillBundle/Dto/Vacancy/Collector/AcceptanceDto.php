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

namespace Veslo\AnthillBundle\Dto\Vacancy\Collector;

use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;

/**
 * Context of vacancy data that has been accepted for persisting and research
 */
class AcceptanceDto
{
    /**
     * Assigned identifier for a newly created vacancy entity
     *
     * @var int|null
     */
    private $vacancyId;

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
     * AcceptanceDto constructor.
     */
    public function __construct()
    {
        $this->conditions = [];
    }

    /**
     * Returns assigned identifier for a newly created vacancy entity
     *
     * @return int|null
     */
    public function getVacancyId(): ?int
    {
        return $this->vacancyId;
    }

    /**
     * Sets assigned identifier for a newly created vacancy entity
     *
     * @param int $vacancyId Assigned identifier for a newly created vacancy entity
     *
     * @return void
     */
    public function setVacancyId(int $vacancyId): void
    {
        $this->vacancyId = $vacancyId;
    }

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
