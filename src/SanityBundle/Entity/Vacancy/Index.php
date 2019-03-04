<?php

declare(strict_types=1);

namespace Veslo\SanityBundle\Entity\Vacancy;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sanity index
 *
 * @ORM\Table(name="sanity_vacancy_index")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="index")
 */
class Index
{
    /**
     * Sanity index identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Sanity index identifier"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Vacancy identifier to which sanity index belongs to
     *
     * @var int
     *
     * @ORM\Column(
     *     name="vacancyId",
     *     type="integer",
     *     unique=true,
     *     options={"comment": "Vacancy identifier to which sanity index belongs to"}
     * )
     */
    private $vacancyId;

    /**
     * Sanity index value, from 0.00 to 100.00
     *
     * @var float
     *
     * @ORM\Column(
     *     name="value",
     *     type="decimal",
     *     precision=5,
     *     scale=2,
     *     options={"unsigned": true, "comment": "Sanity index value, from 0.00 to 100.00"}
     * )
     */
    private $value;

    /**
     * Date and time when sanity index was created
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(
     *     name="indexation_date",
     *     type="datetime",
     *     options={"comment": "Date and time when sanity index was created"}
     * )
     */
    private $indexationDate;

    /**
     * Returns sanity index identifier
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * Returns sanity index value, from 0.00 to 100.00
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Sets sanity index value
     *
     * @param float $value Sanity index value, from 0.00 to 100.00
     *
     * @return void
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
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
