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

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Entity\Repository\VacancyRepository;
use Veslo\AnthillBundle\Entity\Vacancy;

/**
 * Extracts a vacancy instance from the specified context
 */
class Resolver
{
    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * Resolver constructor.
     *
     * @param VacancyRepository $vacancyRepository Vacancy repository
     */
    public function __construct(VacancyRepository $vacancyRepository)
    {
        $this->vacancyRepository = $vacancyRepository;
    }

    /**
     * Returns a vacancy instance related to the specified parsed data, if exists, null otherwise
     *
     * @param ParsedDto $scanResult Context of parsed vacancy data from website
     *
     * @return Vacancy|null
     */
    public function resolveByParsedDto(ParsedDto $scanResult): ?Vacancy
    {
        $location    = $scanResult->getLocation();
        $roadmap     = $location->getRoadmap();
        $roadmapName = $roadmap->getName();

        $vacancy            = $scanResult->getVacancy();
        $externalIdentifier = $vacancy->getExternalIdentifier();

        $entity = $this->vacancyRepository->findByRoadmapNameAndExternalIdentifier($roadmapName, $externalIdentifier);

        return $entity;
    }
}
