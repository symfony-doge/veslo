<?php

declare(strict_types=1);

namespace Veslo\SanityBundle\Entity\Vacancy\Tag;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Veslo\SanityBundle\Entity\Vacancy\Tag;

/**
 * Group for sanity tags
 *
 * @ORM\Table(name="sanity_vacancy_tag_group")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="index")
 */
class Group
{
    /**
     * Identifier for group of sanity tags
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Identifier for group of sanity tags"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Sanity tags group name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, options={"comment": "Sanity tags group name"})
     */
    private $name;

    /**
     * Sanity tags group description
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text", options={"comment": "Sanity tags group description"})
     */
    private $description;

    /**
     * Sanity tags group color
     *
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, options={"comment": "Sanity tags group color"})
     */
    private $color;

    /**
     * Sanity tags in this group
     *
     * @var Collection<Tag>
     *
     * @ORM\OneToMany(targetEntity="Veslo\SanityBundle\Entity\Vacancy\Tag", mappedBy="group")
     */
    private $tags;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Returns identifier for group of sanity tags
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns sanity tags group name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets sanity tags group name
     *
     * @param string $name Sanity tags group name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns sanity tags group description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets sanity tags group description
     *
     * @param string $description Sanity tags group description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns sanity tags group color
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Sets sanity tags group color
     *
     * @param string $color Sanity tags group color
     *
     * @return void
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * Returns sanity tags that belongs to the group
     *
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags->toArray();
    }

    /**
     * Adds a sanity tag to the group
     *
     * @param Tag $tag Sanity tag
     *
     * @return void
     */
    public function addTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
        $tag->setGroup($this);
    }
}
