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
}
