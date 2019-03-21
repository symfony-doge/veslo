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

namespace Veslo\SanityBundle\Dto\Vacancy\Tag;

/**
 * Context of vacancy tags group data
 */
class GroupDto
{
    /**
     * Vacancy tags group name
     *
     * @var string
     */
    private $name;

    /**
     * Tags group description
     *
     * @var string|null
     */
    private $description;

    /**
     * Tags group color
     *
     * @var string|null
     */
    private $color;

    /**
     * Returns vacancy tags group name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets vacancy tags group name
     *
     * @param string $name Tags group name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns tags group description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets tags group description
     *
     * @param string $description Tags group description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns tags group color
     *
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Sets tags group color
     *
     * @param string $color Tags group color
     *
     * @return void
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}
