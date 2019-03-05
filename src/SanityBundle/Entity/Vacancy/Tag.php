<?php

declare(strict_types=1);

namespace Veslo\SanityBundle\Entity\Vacancy;

use Doctrine\ORM\Mapping as ORM;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;

/**
 * Sanity tag
 *
 * @ORM\Table(name="sanity_vacancy_tag")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="index")
 */
class Tag
{
    /**
     * Sanity tag identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Sanity tag identifier"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Group to which sanity tag belongs to
     *
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Veslo\SanityBundle\Entity\Vacancy\Tag\Group", inversedBy="tags")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $group;

    /**
     * Sanity tag name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, options={"comment": "Sanity tag name"})
     */
    private $name;

    /**
     * Sanity tag description
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text", options={"comment": "Sanity tag description"})
     */
    private $description;

    /**
     * Sanity tag color
     *
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, options={"comment": "Sanity tag color"})
     */
    private $color;

    /**
     * Sanity tag image URL
     *
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, options={"comment": "Sanity tag image URL"})
     */
    private $imageUrl;

    /**
     * Returns sanity index identifier
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns group to which sanity tag belongs to
     *
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * Sets group to which sanity tag belongs to
     *
     * @param Group $group Group to which sanity tag belongs to
     *
     * @return void
     */
    public function setGroup(Group $group): void
    {
        // Group change for sanity tag normally should not happen.
        // We do not directly control sanity index data, it comes from external source.
        if ($this->group instanceof Group) {
            return;
        }

        $this->group = $group;
        $group->addTag($this);
    }

    /**
     * Returns sanity tag name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets sanity tag name
     *
     * @param string $name Sanity tag name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns sanity tag description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets sanity tag description
     *
     * @param string $description Sanity tag description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns sanity tag color
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Sets sanity tag color
     *
     * @param string $color Sanity tag color
     *
     * @return void
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * Returns sanity tag image URL
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * Sets sanity tag image URL
     *
     * @param string $imageUrl Sanity tag image URL
     *
     * @return void
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
