<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AnthillBundle\Entity\Vacancy\MappedSuperclass\SynchronizableDataFields;

/**
 * Vacancy entity, contains metadata; all fields which represent a real vacancy data from job websites
 * are described by mapped superclass for better code readability
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
 * @ORM\Entity(repositoryClass="Veslo\AnthillBundle\Entity\Repository\VacancyRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="vacancies")
 * @Gedmo\Loggable(logEntryClass="Veslo\AnthillBundle\Entity\Vacancy\History\Entry")
 * @Gedmo\SoftDeleteable(fieldName="deletionDate", timeAware=true, hardDelete=false)
 */
class Vacancy extends SynchronizableDataFields
{
    // Note: traits are not used for better contextual readability, all fields should be explicit in one scope.

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
     * Alternative SEO-friendly vacancy identifier
     *
     * @var string
     *
     * @ORM\Column(
     *     name="slug",
     *     type="string",
     *     length=255,
     *     unique=true,
     *     options={"comment": "Alternative SEO-friendly vacancy identifier"}
     * )
     * @Gedmo\Slug(fields={"id", "title", "regionName"})
     */
    private $slug;

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
     * Last time when vacancy data has been fetched from external job website
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(
     *     name="synchronization_date",
     *     type="datetime",
     *     options={"comment": "Last time when vacancy data has been fetched from external job website"}
     * )
     */
    private $synchronizationDate;

    /**
     * Date when vacancy has been deleted
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(
     *     name="deletion_date",
     *     type="datetime",
     *     nullable=true,
     *     options={"comment": "Date when vacancy has been deleted"}
     * )
     */
    private $deletionDate;

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
     * Returns alternative SEO-friendly vacancy identifier
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
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
     * Returns last time when vacancy data has been changed
     *
     * @return DateTimeInterface
     */
    public function getSynchronizationDate(): DateTimeInterface
    {
        return $this->synchronizationDate;
    }

    /**
     * Sets last time when vacancy data has been fetched from external job website
     *
     * @param DateTimeInterface $synchronizationDate Last time when vacancy data has been changed
     *
     * @return void
     */
    public function setSynchronizationDate(DateTimeInterface $synchronizationDate): void
    {
        $this->synchronizationDate = $synchronizationDate;
    }

    /**
     * Returns date when vacancy has been deleted
     *
     * @return DateTimeInterface
     */
    public function getDeletionDate(): DateTimeInterface
    {
        return $this->deletionDate;
    }
}
