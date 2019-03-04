<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Company
 *
 * @ORM\Table(name="anthill_company")
 * @ORM\Entity
 * @ORM\Cache(usage="READ_ONLY", region="vacancies")
 * @Gedmo\Loggable(logEntryClass="Veslo\AnthillBundle\Entity\Company\History\Entry")
 * @Gedmo\SoftDeleteable(fieldName="deletionDate", hardDelete=false)
 */
class Company
{
    /**
     * Company identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Company identifier"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Company name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, options={"comment": "Company name"})
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * Company website URL
     *
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true, options={"comment": "Company website URL"})
     * @Gedmo\Versioned
     */
    private $url;

    /**
     * Company logo URL
     *
     * @var string|null
     *
     * @ORM\Column(name="logo_url", type="string", length=255, nullable=true, options={"comment": "Company logo URL"})
     * @Gedmo\Versioned
     */
    private $logoUrl;

    /**
     * Company vacancies
     *
     * @var Collection<Vacancy>
     *
     * @ORM\OneToMany(targetEntity="Veslo\AnthillBundle\Entity\Vacancy", mappedBy="company", cascade={"remove"})
     */
    private $vacancies;

    /**
     * Last time when company data has been fetched from external job website
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(
     *     name="synchronization_date",
     *     type="datetime",
     *     options={"comment": "Last time when company data has been fetched from external job website"}
     * )
     */
    private $synchronizationDate;

    /**
     * Date when company has been deleted
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(
     *     name="deletion_date",
     *     type="datetime",
     *     nullable=true,
     *     options={"comment": "Date when company has been deleted"}
     * )
     */
    private $deletionDate;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->vacancies = new ArrayCollection();
    }

    /**
     * Returns company identifier
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns company name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets company name
     *
     * @param string $name Company name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns company website URL
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets company website URL
     *
     * @param string|null $url Company website URL
     *
     * @return void
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * Returns company logo URL
     *
     * @return string|null Company logo URL
     */
    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    /**
     * Sets company logo URL
     *
     * @param string|null $logoUrl Company logo URL
     *
     * @return void
     */
    public function setLogoUrl(?string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
    }

    /**
     * Returns company vacancies
     *
     * @return Vacancy[]
     */
    public function getVacancies(): array
    {
        return $this->vacancies->toArray();
    }

    /**
     * Adds a vacancy relation to the company
     *
     * @param Vacancy $vacancy Vacancy
     *
     * @return void
     */
    public function addVacancy(Vacancy $vacancy): void
    {
        if ($this->vacancies->contains($vacancy)) {
            return;
        }

        $this->vacancies->add($vacancy);
        $vacancy->setCompany($this);
    }

    /**
     * Returns last time when company data has been changed
     *
     * @return DateTimeInterface
     */
    public function getSynchronizationDate(): DateTimeInterface
    {
        return $this->synchronizationDate;
    }

    /**
     * Sets last time when company data has been fetched from external job website
     *
     * @param DateTimeInterface $synchronizationDate Last time when company data has been changed
     *
     * @return void
     */
    public function setSynchronizationDate(DateTimeInterface $synchronizationDate): void
    {
        $this->synchronizationDate = $synchronizationDate;
    }

    /**
     * Returns date when company has been deleted
     *
     * @return DateTimeInterface
     */
    public function getDeletionDate(): DateTimeInterface
    {
        return $this->deletionDate;
    }
}
