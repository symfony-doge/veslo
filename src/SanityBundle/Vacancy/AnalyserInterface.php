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

namespace Veslo\SanityBundle\Vacancy;

use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\SanityBundle\Entity\Vacancy\Index as VacancyIndex;

/**
 * Should be implemented by service that generates index, tags and other sanity data for a vacancy
 */
interface AnalyserInterface
{
    /**
     * Performs a contextual analysis of vacancy data and returns assigned sanity index
     *
     * @param Vacancy $vacancy Vacancy entity
     *
     * @return VacancyIndex
     */
    public function analyse(Vacancy $vacancy): VacancyIndex;
}
