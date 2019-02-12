<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Veslo\AnthillBundle\Vacancy\Roadmap\Configuration\ParametersInterface;

/**
 * Parameters for vacancy searching on HeadHunter website
 *
 * @ORM\Table(name="anthill_vacancy_roadmap_headhunter")
 * @ORM\Entity(repositoryClass="Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters\HeadHunterRepository")
 * @ORM\Cache(usage="READ_WRITE", region="roadmap_parameters_head_hunter")
 */
class HeadHunter implements ParametersInterface
{
    /**
     * DateTime format
     *
     * @const string
     */
    public const DATETIME_FORMAT = 'Y-m-d\TH:i:s';

    /**
     * Parameters record identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Roadmap configuration key to which parameters belongs to
     *
     * @var string
     *
     * @ORM\Column(name="configuration_key", type="string", length=255, unique=true)
     */
    private $configurationKey;

    /**
     * Vacancy website URL
     *
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * Search query text
     *
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * Vacancy area
     *
     * @var int
     *
     * @ORM\Column(name="area", type="integer")
     */
    private $area;

    /**
     * Publication date from
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(name="date_from", type="datetime")
     */
    private $dateFrom;

    /**
     * Publication date to
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(name="date_to", type="datetime")
     */
    private $dateTo;

    /**
     * Order by criteria
     *
     * @var string
     *
     * @ORM\Column(name="order_by", type="string", length=255)
     */
    private $orderBy;

    /**
     * Number of vacancies to fetch for a single page
     *
     * @var int
     *
     * @ORM\Column(name="per_page", type="integer")
     */
    private $perPage;

    /**
     * Vacancies received during specified publication date range
     *
     * @var int
     *
     * @ORM\Column(name="received", type="integer")
     */
    private $received;

    /**
     * Returns configuration parameters identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }

    /**
     * Sets configuration key to which parameters belongs to
     *
     * @param string $configurationKey Configuration key to which parameters belongs to
     *
     * @return void
     */
    public function setConfigurationKey(string $configurationKey): void
    {
        $this->configurationKey = $configurationKey;
    }

    /**
     * Returns vacancy website URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets vacancy website URL
     *
     * @param string $url Website URL
     *
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Returns search query text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets search query text
     *
     * @param string $text Search query text
     *
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Returns vacancy area
     *
     * @return int
     */
    public function getArea(): int
    {
        return $this->area;
    }

    /**
     * Sets vacancy area
     *
     * @param int $area Vacancy area
     *
     * @return void
     */
    public function setArea(int $area): void
    {
        $this->area = $area;
    }

    /**
     * Returns publication date "from" part for searching vacancies within range
     *
     * @return DateTimeInterface
     */
    public function getDateFrom(): DateTimeInterface
    {
        return $this->dateFrom;
    }

    /**
     * Returns publication date "from" formatted for query argument
     *
     * @return string
     */
    public function getDateFromFormatted(): string
    {
        if (!$this->dateFrom instanceof DateTimeInterface) {
            return '';
        }

        return $this->dateFrom->format(self::DATETIME_FORMAT);
    }

    /**
     * Sets publication date from
     *
     * @param DateTimeInterface $dateFrom Publication date "from" part for searching vacancies within range
     *
     * @return void
     */
    public function setDateFrom(DateTimeInterface $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * Returns publication date "to" part for searching vacancies within range
     *
     * @return DateTimeInterface
     */
    public function getDateTo(): DateTimeInterface
    {
        return $this->dateTo;
    }

    /**
     * Returns publication date "to" formatted for query argument
     *
     * @return string
     */
    public function getDateToFormatted(): string
    {
        if (!$this->dateTo instanceof DateTimeInterface) {
            return '';
        }

        return $this->dateTo->format(self::DATETIME_FORMAT);
    }

    /**
     * Sets publication date to
     *
     * @param DateTimeInterface $dateTo Publication date "to" part for searching vacancies within range
     *
     * @return void
     */
    public function setDateTo(DateTimeInterface $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    /**
     * Returns order by criteria
     *
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * Sets order by criteria
     *
     * @param string $orderBy Order by criteria
     *
     * @return void
     */
    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * Returns number of vacancies to fetch for a single page
     *
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Sets number of vacancies to fetch for a single page
     *
     * @param int $perPage Number of vacancies to fetch for a single page
     *
     * @return void
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * Returns received vacancies count during specified publication date range
     *
     * @return int
     */
    public function getReceived(): int
    {
        return $this->received;
    }

    /**
     * Sets vacancies received count
     *
     * @param int $received Received vacancies count during specified publication date range
     *
     * @return void
     */
    public function setReceived(int $received): void
    {
        $this->received = $received;
    }
}
