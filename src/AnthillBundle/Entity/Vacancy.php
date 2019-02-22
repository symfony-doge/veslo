<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity;

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
}
