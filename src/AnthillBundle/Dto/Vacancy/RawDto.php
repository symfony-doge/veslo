<?php

namespace Veslo\AnthillBundle\Dto\Vacancy;

use DateTime;

/**
 * Context of raw vacancy data from website for analysis
 *
 * This is just a raw data from specific website, it is not compatible with any project schema
 * Decisions which data will be stored in actual schema are performed at collecting stage
 */
class RawDto
{
    /**
     * Vacancy URL
     *
     * @var string
     */
    private $url;

    /**
     * Vacancy region name
     *
     * @var string
     */
    private $regionName;

    /**
     * Vacancy company name
     *
     * @var string
     */
    private $companyName;

    /**
     * Vacancy company logo url
     *
     * @var string
     */
    private $companyLogoUrl;

    /**
     * Vacancy company website url
     *
     * @var string
     */
    private $companyUrl;

    /**
     * Vacancy title
     *
     * @var string
     */
    private $title;

    /**
     * Vacancy preview text
     *
     * @var string
     */
    private $snippet;

    /**
     * Vacancy text
     *
     * @var string
     */
    private $text;

    /**
     * Vacancy salary amount from
     *
     * @var int
     */
    private $salaryFrom;

    /**
     * Vacancy salary amount to
     *
     * @var int
     */
    private $salaryTo;

    /**
     * Vacancy publication date
     *
     * @var DateTime
     */
    private $publicationDate;

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
     * Returns vacancy URL
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
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
     * Returns vacancy region name
     *
     * @return string|null
     */
    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    /**
     * Sets vacancy company name
     *
     * @param string $companyName Vacancy company name
     *
     * @return void
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * Returns vacancy company name
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * Sets vacancy company logo URL
     *
     * @param string $companyLogoUrl Vacancy company logo URL
     *
     * @return void
     */
    public function setCompanyLogoUrl(string $companyLogoUrl): void
    {
        $this->companyLogoUrl = $companyLogoUrl;
    }

    /**
     * Returns vacancy company logo URL
     *
     * @return string|null
     */
    public function getCompanyLogoUrl(): ?string
    {
        return $this->companyLogoUrl;
    }

    /**
     * Sets vacancy company URL
     *
     * @param string $companyUrl Vacancy company URL
     *
     * @return void
     */
    public function setCompanyUrl(string $companyUrl): void
    {
        $this->companyUrl = $companyUrl;
    }

    /**
     * Returns vacancy company URL
     *
     * @return string|null
     */
    public function getCompanyUrl(): ?string
    {
        return $this->companyUrl;
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
     * Returns vacancy title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets vacancy preview text
     *
     * @param string $snippet Vacancy preview text
     *
     * @return void
     */
    public function setSnippet(string $snippet): void
    {
        $this->snippet = $snippet;
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
     * Returns vacancy text
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Sets vacancy salary amount from
     *
     * @param int $salaryFrom Vacancy salary amount from
     *
     * @return void
     */
    public function setSalaryFrom(int $salaryFrom): void
    {
        $this->salaryFrom = $salaryFrom;
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
     * Sets vacancy salary amount to
     *
     * @param int $salaryTo Vacancy salary amount to
     *
     * @return void
     */
    public function setSalaryTo(int $salaryTo): void
    {
        $this->salaryTo = $salaryTo;
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
     * Sets vacancy publication date
     *
     * @param DateTime $publicationDate Vacancy publication date
     *
     * @return void
     */
    public function setPublicationDate(DateTime $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * Returns vacancy publication date
     *
     * @return DateTime|null
     */
    public function getPublicationDate(): ?DateTime
    {
        return $this->publicationDate;
    }
}
