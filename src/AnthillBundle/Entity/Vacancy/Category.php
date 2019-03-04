<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Vacancy;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Veslo\AnthillBundle\Entity\Vacancy;

/**
 * Vacancy category
 *
 * @ORM\Table(name="anthill_vacancy_category")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="vacancies")
 */
class Category
{
    /**
     * Vacancy category identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Vacancy category identifier"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Vacancy category name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, options={"comment": "Vacancy category name"})
     */
    private $name;

    /**
     * Vacancies that belongs to category
     *
     * @var Collection<Vacancy>
     *
     * @ORM\ManyToMany(targetEntity="Veslo\AnthillBundle\Entity\Vacancy", mappedBy="categories")
     */
    private $vacancies;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->vacancies = new ArrayCollection();
    }

    /**
     * Returns vacancy category identifier
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets vacancy category name
     *
     * @param string $name Vacancy category name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns vacancy category name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns vacancies that belongs to the category
     *
     * @return Vacancy[]
     */
    public function getVacancies(): array
    {
        return $this->vacancies->toArray();
    }

    /**
     * Adds a vacancy to the category
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
        $vacancy->addCategory($this);
    }

    /**
     * Removes a vacancy from the category
     *
     * @param Vacancy $vacancy Vacancy
     *
     * @return void
     */
    public function removeVacancy(Vacancy $vacancy): void
    {
        if (!$this->vacancies->contains($vacancy)) {
            return;
        }

        $this->vacancies->removeElement($vacancy);
        $vacancy->removeCategory($this);
    }
}
