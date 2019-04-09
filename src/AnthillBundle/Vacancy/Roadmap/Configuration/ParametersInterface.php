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

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Configuration;

/**
 * Represents search criteria parameters for roadmap configuration
 */
interface ParametersInterface
{
    /**
     * Returns configuration key to which parameters record belongs to
     *
     * @return string
     */
    public function getConfigurationKey(): string;

    /**
     * Returns vacancy provider's URI
     *
     * @return string
     */
    public function getProviderUri(): string;
}
