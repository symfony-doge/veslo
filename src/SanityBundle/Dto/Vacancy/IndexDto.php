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

namespace Veslo\SanityBundle\Dto\Vacancy;

use DateTimeInterface;

/**
 * Context of vacancy index data
 */
class IndexDto
{
    /**
     * Vacancy index value
     *
     * @var float
     */
    private $value;

    /**
     * Vacancy identifier to which sanity index belongs to
     *
     * @var int
     */
    private $vacancyId;

    /**
     * Vacancy tags
     *
     * @var TagDto[]
     */
    private $tags;

    /**
     * Date and time when sanity index was created
     *
     * @var DateTimeInterface
     */
    private $indexationDate;

    /**
     * Returns vacancy index value
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Sets vacancy index value
     *
     * @param float $value Index value
     *
     * @return void
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * Returns vacancy identifier to which sanity index belongs to
     *
     * @return int
     */
    public function getVacancyId(): int
    {
        return $this->vacancyId;
    }

    /**
     * Sets vacancy identifier to which sanity index belongs to
     *
     * @param int $vacancyId Vacancy identifier to which sanity index belongs to
     *
     * @return void
     */
    public function setVacancyId(int $vacancyId): void
    {
        $this->vacancyId = $vacancyId;
    }

    /**
     * Returns vacancy tags
     *
     * @return TagDto[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Adds a vacancy tag
     *
     * @param TagDto $tag Vacancy tag
     *
     * @return void
     */
    public function addTag(TagDto $tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * Returns date and time when sanity index was created
     *
     * @return DateTimeInterface
     */
    public function getIndexationDate(): DateTimeInterface
    {
        return $this->indexationDate;
    }

    /**
     * Sets date and time when sanity index was created
     *
     * @param DateTimeInterface $indexationDate Date and time when sanity index was created
     *
     * @return void
     */
    public function setIndexationDate(DateTimeInterface $indexationDate): void
    {
        $this->indexationDate = $indexationDate;
    }
}
