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

namespace Veslo\AnthillBundle\Dto\Vacancy;

use DateTime;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;

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
     * Unique vacancy identifier on provider's website
     *
     * @var string
     */
    private $externalIdentifier;

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
     * Vacancy company website url
     *
     * @var string|null
     */
    private $companyUrl;

    /**
     * Vacancy company logo url
     *
     * @var string|null
     */
    private $companyLogoUrl;

    /**
     * Vacancy title
     *
     * @var string
     */
    private $title;

    /**
     * Vacancy preview text
     *
     * @var string|null
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
     * @var int|null
     */
    private $salaryFrom;

    /**
     * Vacancy salary amount to
     *
     * @var int|null
     */
    private $salaryTo;

    /**
     * Vacancy salary currency
     *
     * @var string|null
     */
    private $salaryCurrency;

    /**
     * Vacancy publication date
     *
     * @var DateTimeInterface
     */
    private $publicationDate;

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
     * Returns unique vacancy identifier on provider's website
     *
     * @return string
     */
    public function getExternalIdentifier(): string
    {
        return $this->externalIdentifier;
    }

    /**
     * Sets unique vacancy identifier on provider's website
     *
     * @param string $externalIdentifier Unique vacancy identifier on provider's website
     *
     * @return void
     */
    public function setExternalIdentifier(string $externalIdentifier): void
    {
        $this->externalIdentifier = $externalIdentifier;
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
     * Returns vacancy company name
     *
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
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
     * Returns vacancy company logo URL
     *
     * @return string|null
     */
    public function getCompanyLogoUrl(): ?string
    {
        return $this->companyLogoUrl;
    }

    /**
     * Sets vacancy company logo URL
     *
     * @param string|null $companyLogoUrl Vacancy company logo URL
     *
     * @return void
     */
    public function setCompanyLogoUrl(?string $companyLogoUrl): void
    {
        $this->companyLogoUrl = $companyLogoUrl;
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
     * Sets vacancy company URL
     *
     * @param string|null $companyUrl Vacancy company URL
     *
     * @return void
     */
    public function setCompanyUrl(?string $companyUrl): void
    {
        $this->companyUrl = $companyUrl;
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
     * @param DateTimeInterface|string $publicationDate Vacancy publication date
     *
     * @return void
     *
     * @throws Exception
     */
    public function setPublicationDate($publicationDate): void
    {
        if ($publicationDate instanceof DateTimeInterface) {
            $this->publicationDate = $publicationDate;
        } elseif (is_string($publicationDate)) {
            $this->publicationDate = new DateTime($publicationDate);
        } else {
            throw new InvalidArgumentException(
                'Publication date should be either instanceof DateTimeInterface or string'
            );
        }
    }
}
