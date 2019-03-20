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

namespace Veslo\SanityBundle\Dto\Vacancy;

use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;

/**
 * Context of vacancy tag data
 */
class TagDto
{
    /**
     * Vacancy tag name
     *
     * @var string
     */
    private $name;

    /**
     * Vacancy tags group
     *
     * @var GroupDto
     */
    private $group;

    /**
     * Vacancy tag title
     *
     * @var string
     */
    private $title;

    /**
     * Vacancy tag description
     *
     * @var string
     */
    private $description;

    /**
     * Vacancy tag color
     *
     * @var string
     */
    private $color;

    /**
     * Vacancy tag image URL
     *
     * @var string
     */
    private $imageUrl;

    /**
     * Returns vacancy tag name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets vacancy tag name
     *
     * @param string $name Vacancy tag name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns vacancy tags group
     *
     * @return GroupDto
     */
    public function getGroup(): GroupDto
    {
        return $this->group;
    }

    /**
     * Sets vacancy tags group
     *
     * @param GroupDto $group Tags group
     *
     * @return void
     */
    public function setGroup(GroupDto $group): void
    {
        $this->group = $group;
    }

    /**
     * Returns vacancy tag title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets vacancy tag title
     *
     * @param string $title Vacancy tag title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns vacancy tag description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets vacancy tag description
     *
     * @param string $description Vacancy tag description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns vacancy tag color
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Sets vacancy tag color
     *
     * @param string $color Vacancy tag color
     *
     * @return void
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * Returns vacancy tag image URL
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * Sets vacancy tag image URL
     *
     * @param string $imageUrl Vacancy tag image URL
     *
     * @return void
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
