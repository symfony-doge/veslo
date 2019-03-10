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

namespace Veslo\AnthillBundle\Dto\Vacancy\Roadmap;

use Veslo\AnthillBundle\Vacancy\Normalizer\Roadmap\StrategyDtoDenormalizer;

/**
 * Context of searching algorithm used by roadmap
 *
 * @see StrategyDtoDenormalizer
 */
class StrategyDto
{
    /**
     * Property for strategy name
     *
     * @const string
     */
    public const PROPERTY_NAME = 'name';

    /**
     * Searching algorithm name
     *
     * @var string
     */
    private $name;

    /**
     * Sets name for searching algorithm used by roadmap
     *
     * @param string $name Searching algorithm name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns name of searching algorithm used by roadmap
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
