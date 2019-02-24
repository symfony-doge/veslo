<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Vacancy\MappedSuperclass;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Veslo\AnthillBundle\Entity\Vacancy;

/**
 * Surrogate class for managing complexity of main vacancy entity
 * Contains base entity fields only which will be synchronized with data from an external job website
 *
 * @ORM\MappedSuperclass
 *
 * @see Vacancy
 */
abstract class SynchronizableDataFields
{
    /**
     * Vacancy URL
     *
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, options={"comment": "Vacancy URL"})
     * @Gedmo\Versioned
     */
    protected $url;

    /**
     * Vacancy region name
     *
     * @var string
     *
     * @ORM\Column(name="region_name", type="string", length=255, options={"comment": "Vacancy region name"})
     * @Gedmo\Versioned
     */
    protected $regionName;

    /**
     * Vacancy title
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, options={"comment": "Vacancy title"})
     * @Gedmo\Versioned
     */
    protected $title;

    /**
     * Vacancy preview text
     *
     * @var string|null
     *
     * @ORM\Column(name="snippet", type="string", nullable=true, options={"comment": "Vacancy preview text"})
     * @Gedmo\Versioned
     */
    protected $snippet;

    /**
     * Vacancy text
     *
     * @var string
     *
     * @ORM\Column(name="text", type="string", options={"comment": "Vacancy text"})
     * @Gedmo\Versioned
     */
    protected $text;

    /**
     * Vacancy salary amount from
     *
     * @var int|null
     *
     * @ORM\Column(
     *     name="salary_from",
     *     type="integer",
     *     nullable=true,
     *     options={"unsigned": true, "comment": "Vacancy salary amount from"}
     * )
     * @Gedmo\Versioned
     */
    protected $salaryFrom;

    /**
     * Vacancy salary amount to
     *
     * @var int|null
     *
     * @ORM\Column(
     *     name="salary_to",
     *     type="integer",
     *     nullable=true,
     *     options={"unsigned": true, "comment": "Vacancy salary amount to"}
     * )
     * @Gedmo\Versioned
     */
    protected $salaryTo;

    /**
     * Vacancy salary currency
     *
     * @var string|null
     *
     * @ORM\Column(
     *     name="salary_currency",
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     options={"comment": "Vacancy salary currency"}
     * )
     * @Gedmo\Versioned
     */
    protected $salaryCurrency;

    /**
     * Vacancy publication date
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(name="publication_date", type="datetime", options={"comment": "Vacancy publication date"})
     */
    protected $publicationDate;

    /**
     * Returns vacancy URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets vacancy URL
     *
     * @param string $url Vacancy URL
     *
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Returns vacancy region name
     *
     * @return string
     */
    public function getRegionName(): string
    {
        return $this->regionName;
    }

    /**
     * Sets vacancy region name
     *
     * @param string $regionName Vacancy region name
     *
     * @return void
     */
    public function setRegionName(string $regionName): void
    {
        $this->regionName = $regionName;
    }

    /**
     * Returns vacancy title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets vacancy title
     *
     * @param string $title Vacancy title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns vacancy preview text
     *
     * @return string|null
     */
    public function getSnippet(): ?string
    {
        return $this->snippet;
    }

    /**
     * Sets vacancy preview text
     *
     * @param string|null $snippet Vacancy preview text
     *
     * @return void
     */
    public function setSnippet(?string $snippet): void
    {
        $this->snippet = $snippet;
    }

    /**
     * Returns vacancy text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets vacancy text
     *
     * @param string $text Vacancy text
     *
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Returns vacancy salary amount from
     *
     * @return int|null
     */
    public function getSalaryFrom(): ?int
    {
        return $this->salaryFrom;
    }

    /**
     * Sets vacancy salary amount from
     *
     * @param int|null $salaryFrom Vacancy salary amount from
     *
     * @return void
     */
    public function setSalaryFrom(?int $salaryFrom): void
    {
        $this->salaryFrom = $salaryFrom;
    }

    /**
     * Returns vacancy salary amount to
     *
     * @return int|null
     */
    public function getSalaryTo(): ?int
    {
        return $this->salaryTo;
    }

    /**
     * Sets vacancy salary amount to
     *
     * @param int|null $salaryTo Vacancy salary amount to
     *
     * @return void
     */
    public function setSalaryTo(?int $salaryTo): void
    {
        $this->salaryTo = $salaryTo;
    }

    /**
     * Returns vacancy salary currency
     *
     * @return string|null
     */
    public function getSalaryCurrency(): ?string
    {
        return $this->salaryCurrency;
    }

    /**
     * Sets vacancy salary currency
     *
     * @param string|null $salaryCurrency Vacancy salary currency
     *
     * @return void
     */
    public function setSalaryCurrency(?string $salaryCurrency): void
    {
        $this->salaryCurrency = $salaryCurrency;
    }

    /**
     * Returns vacancy publication date
     *
     * @return DateTimeInterface
     */
    public function getPublicationDate(): DateTimeInterface
    {
        return $this->publicationDate;
    }

    /**
     * Sets vacancy publication date
     *
     * @param DateTimeInterface $publicationDate Vacancy publication date
     *
     * @return void
     */
    public function setPublicationDate(DateTimeInterface $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }
}
