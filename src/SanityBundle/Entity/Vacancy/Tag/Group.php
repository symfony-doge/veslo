<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\SanityBundle\Entity\Vacancy\Tag;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Veslo\SanityBundle\Entity\Vacancy\Tag;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group\Translation\Entry;

/**
 * Group for sanity tags
 *
 * @ORM\Table(name="sanity_vacancy_tag_group")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="index")
 * @Gedmo\TranslationEntity(class="Veslo\SanityBundle\Entity\Vacancy\Tag\Group\Translation\Entry")
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
     * @ORM\Column(name="description", type="text", nullable=true, options={"comment": "Sanity tags group description"})
     * @Gedmo\Translatable
     */
    private $description;

    /**
     * Sanity tags group color
     *
     * @var string
     *
     * @ORM\Column(
     *     name="color",
     *     type="string",
     *     length=255,
     *     nullable=true,
     *     options={"comment": "Sanity tags group color"}
     * )
     */
    private $color;

    /**
     * Translation entries for sanity group fields
     *
     * @var Collection<Entry>
     *
     * @ORM\OneToMany(
     *     targetEntity="Veslo\SanityBundle\Entity\Vacancy\Tag\Group\Translation\Entry",
     *     mappedBy="object",
     *     cascade={"persist"}
     * )
     */
    private $translations;

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
        $this->translations = new ArrayCollection();
        $this->tags         = new ArrayCollection();
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
     * Returns translation entries for sanity group fields
     *
     * @return Entry[]
     */
    public function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    /**
     * Adds a translation entry for sanity group field
     *
     * @param Entry $translation Sanity group translation entry
     *
     * @return void
     */
    public function addTranslation(Entry $translation): void
    {
        if ($this->translations->contains($translation)) {
            return;
        }

        $this->translations->add($translation);
        $translation->setObject($this);
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
