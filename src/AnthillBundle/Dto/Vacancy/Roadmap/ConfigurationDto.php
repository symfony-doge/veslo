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

use Veslo\AnthillBundle\Vacancy\Normalizer\Roadmap\ConfigurationDtoDenormalizer;

/**
 * Context of configuration for search algorithm used by roadmap
 *
 * @see ConfigurationDtoDenormalizer
 */
class ConfigurationDto
{
    /**
     * Property for configuration key
     *
     * @const string
     */
    public const PROPERTY_KEY = 'key';

    /**
     * Configuration key
     *
     * @var string
     */
    private $key;

    /**
     * Sets configuration key
     *
     * @param string $key Configuration key
     *
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Returns configuration key
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }
}
