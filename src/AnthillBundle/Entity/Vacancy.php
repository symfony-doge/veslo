<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Veslo\AnthillBundle\Entity\Vacancy\Category;

/**
 * Vacancy
 *
 * @ORM\Table(
 *     name="anthill_vacancy",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="anthill_vacancy_roadmap_name_external_identifier_uq",
 *             columns={"roadmap_name", "external_identifier"}
 *         )
 *     }
 * )
 * @ORM\Entity
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="vacancies")
 */
class Vacancy
{
    /**
     * Vacancy identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Vacancy identifier"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Roadmap name by which the vacancy has been fetched
     *
     * @var string
     *
     * @ORM\Column(
     *     name="roadmap_name",
     *     type="string",
     *     length=255,
     *     options={"comment": "Roadmap name by which the vacancy has been fetched"}
     * )
     */
    private $roadmapName;

    /**
     * Unique vacancy identifier on provider's website
     *
     * @var string
     *
     * @ORM\Column(
     *     name="external_identifier",
     *     type="string",
     *     length=255,
     *     options={"comment": "Unique vacancy identifier on provider's website"}
     * )
     */
    private $externalIdentifier;

    /**
     * Company that posted the vacancy
     *
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Veslo\AnthillBundle\Entity\Company", inversedBy="vacancies")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * Categories to which vacancy belongs to
     *
     * @var Collection<Category>
     *
     * @ORM\ManyToMany(targetEntity="Veslo\AnthillBundle\Entity\Vacancy\Category", inversedBy="vacancies")
     * @ORM\JoinTable(
     *     name="anthill_vacancy_anthill_vacancy_category",
     *     joinColumns={@ORM\JoinColumn(name="vacancy_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $categories;

    /**
     * Vacancy URL
     *
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, options={"comment": "Vacancy URL"})
     */
    private $url;

    /**
     * Vacancy region name
     *
     * @var string
     *
     * @ORM\Column(name="region_name", type="string", length=255, options={"comment": "Vacancy region name"})
     */
    private $regionName;

    /**
     * Vacancy title
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, options={"comment": "Vacancy title"})
     */
    private $title;

    /**
     * Vacancy preview text
     *
     * @var string|null
     *
     * @ORM\Column(name="snippet", type="string", nullable=true, options={"comment": "Vacancy preview text"})
     */
    private $snippet;

    /**
     * Vacancy text
     *
     * @var string
     *
     * @ORM\Column(name="text", type="string", options={"comment": "Vacancy text"})
     */
    private $text;

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
     */
    private $salaryFrom;

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
     */
    private $salaryTo;

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
     */
    private $salaryCurrency;

    /**
     * Vacancy publication date
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(name="publication_date", type="datetime", options={"comment": "Vacancy publication date"})
     */
    private $publicationDate;

    /**
     * Vacancy constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Returns vacancy identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns roadmap name by which the vacancy has been fetched
     *
     * @return string
     */
    public function getRoadmapName(): string
    {
        return $this->roadmapName;
    }

    /**
     * Sets roadmap name by which the vacancy has been fetched
     *
     * @param string $roadmapName Roadmap name by which the vacancy has been fetched
     *
     * @return void
     */
    public function setRoadmapName(string $roadmapName): void
    {
        $this->roadmapName = $roadmapName;
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
     * Returns company that posted the vacancy
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * Sets company that posted the vacancy
     *
     * @param Company $company Company entity instance
     *
     * @return void
     */
    public function setCompany(Company $company): void
    {
        $this->company = $company;

        $company->addVacancy($this);
    }

    /**
     * Returns categories to which vacancy belongs to
     *
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    /**
     * Adds a category relation to which vacancy belongs to
     *
     * @param Category $category Vacancy category
     *
     * @return void
     */
    public function addCategory(Category $category): void
    {
        if ($this->categories->contains($category)) {
            return;
        }

        $this->categories->add($category);
        $category->addVacancy($this);
    }

    /**
     * Removes a category relation to which vacancy belongs to
     *
     * @param Category $category Vacancy category
     *
     * @return void
     */
    public function removeCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            return;
        }

        $this->categories->removeElement($category);
        $category->removeVacancy($this);
    }

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
