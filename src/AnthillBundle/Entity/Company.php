<?php

namespace Veslo\AnthillBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="anthill_company")
 * @ORM\Entity
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="vacancies")
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
     * @ORM\Column(name="name", type="string", length=255, options={"comment": "Company name"})
     */
    private $name;

    /**
     * Company website URL
     *
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true, options={"comment": "Company website URL"})
     */
    private $url;

    /**
     * Company logo URL
     *
     * @var string|null
     *
     * @ORM\Column(name="logo_url", type="string", length=255, nullable=true, options={"comment": "Company logo URL"})
     */
    private $logoUrl;

    /**
     * Company vacancies
     *
     * @var Collection<Vacancy>
     *
     * @ORM\OneToMany(targetEntity="Veslo\AnthillBundle\Entity\Vacancy", mappedBy="company")
     */
    private $vacancies;

    /**
     * Returns company identifier
     *
     * @return int
     */
    public function getId()
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
}
