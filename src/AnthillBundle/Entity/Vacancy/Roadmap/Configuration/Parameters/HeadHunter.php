<?php

namespace Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Parameters for searching vacancies on HeadHunter website
 *
 * @ORM\Table(name="anthill_vacancy_roadmap_headhunter")
 * @ORM\Entity(repositoryClass="Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters\HeadHunterRepository")
 */
class HeadHunter
{
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
     * @var string
     *
     * @ORM\Column(name="area", type="integer")
     */
    private $area;

    /**
     * Publication date from
     *
     * @var DateTime
     *
     * @ORM\Column(name="date_from", type="datetime")
     */
    private $dateFrom;

    /**
     * Publication date to
     *
     * @var DateTime
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
     * Returns roadmap configuration key to which parameters belongs to
     *
     * @return string
     */
    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
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
     * Returns vacancy area
     *
     * @return string
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * Returns publication date "from" part for searching vacancies within range
     *
     * @return DateTime
     */
    public function getDateFrom(): DateTime
    {
        return $this->dateFrom;
    }

    /**
     * Sets publication date from
     *
     * @param DateTime $dateFrom Publication date "from" part for searching vacancies within range
     *
     * @return void
     */
    public function setDateFrom(DateTime $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * Returns publication date "to" part for searching vacancies within range
     *
     * @return DateTime
     */
    public function getDateTo(): DateTime
    {
        return $this->dateTo;
    }

    /**
     * Sets publication date to
     *
     * @param DateTime $dateTo Publication date "to" part for searching vacancies within range
     *
     * @return void
     */
    public function setDateTo(DateTime $dateTo): void
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
     * Returns number of vacancies to fetch for a single page
     *
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
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
