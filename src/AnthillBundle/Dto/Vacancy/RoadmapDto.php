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

namespace Veslo\AnthillBundle\Dto\Vacancy;

use Veslo\AnthillBundle\Vacancy\Normalizer\RoadmapDtoDenormalizer;

/**
 * Context of roadmap by which the vacancy was found
 *
 * @see RoadmapDtoDenormalizer
 */
class RoadmapDto
{
    /**
     * Property with roadmap name
     *
     * @const string
     */
    public const PROPERTY_NAME = 'name';

    /**
     * Roadmap name
     *
     * @var string
     */
    private $name;

    /**
     * Sets roadmap name
     *
     * @param string $name Roadmap name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns roadmap name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
