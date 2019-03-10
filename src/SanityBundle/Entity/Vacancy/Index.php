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

namespace Veslo\SanityBundle\Entity\Vacancy;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sanity index
 *
 * @ORM\Table(name="sanity_vacancy_index")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="index")
 */
class Index
{
    /**
     * Sanity index identifier
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"comment": "Sanity index identifier"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Vacancy identifier to which sanity index belongs to
     *
     * @var int
     *
     * @ORM\Column(
     *     name="vacancy_id",
     *     type="integer",
     *     unique=true,
     *     options={"comment": "Vacancy identifier to which sanity index belongs to"}
     * )
     */
    private $vacancyId;

    /**
     * Sanity index value, from 0.00 to 100.00
     *
     * @var float
     *
     * @ORM\Column(
     *     name="value",
     *     type="decimal",
     *     precision=5,
     *     scale=2,
     *     options={"unsigned": true, "comment": "Sanity index value, from 0.00 to 100.00"}
     * )
     */
    private $value;

    /**
     * Sanity tags which was offered for vacancy by indexation result
     *
     * @var Collection<Tag>
     *
     * @ORM\ManyToMany(targetEntity="Veslo\SanityBundle\Entity\Vacancy\Tag", inversedBy="indexes")
     * @ORM\JoinTable(
     *     name="sanity_vacancy_index_sanity_vacancy_tag",
     *     joinColumns={@ORM\JoinColumn(name="index_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $tags;

    /**
     * Date and time when sanity index was created
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(
     *     name="indexation_date",
     *     type="datetime",
     *     options={"comment": "Date and time when sanity index was created"}
     * )
     */
    private $indexationDate;

    /**
     * Index constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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
     * Returns vacancy identifier to which sanity index belongs to
     *
     * @return int
     */
    public function getVacancyId(): int
    {
        return $this->vacancyId;
    }

    /**
     * Sets vacancy identifier to which sanity index belongs to
     *
     * @param int $vacancyId Vacancy identifier to which sanity index belongs to
     *
     * @return void
     */
    public function setVacancyId(int $vacancyId): void
    {
        $this->vacancyId = $vacancyId;
    }

    /**
     * Returns sanity index value, from 0.00 to 100.00
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Sets sanity index value
     *
     * @param float $value Sanity index value, from 0.00 to 100.00
     *
     * @return void
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * Returns sanity tags which was offered for vacancy by indexation result
     *
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags->toArray();
    }

    /**
     * Adds a sanity tag which was offered for vacancy by indexation result
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
        $tag->addIndex($this);
    }

    /**
     * Returns date and time when sanity index was created
     *
     * @return DateTimeInterface
     */
    public function getIndexationDate(): DateTimeInterface
    {
        return $this->indexationDate;
    }

    /**
     * Sets date and time when sanity index was created
     *
     * @param DateTimeInterface $indexationDate Date and time when sanity index was created
     *
     * @return void
     */
    public function setIndexationDate(DateTimeInterface $indexationDate): void
    {
        $this->indexationDate = $indexationDate;
    }
}
