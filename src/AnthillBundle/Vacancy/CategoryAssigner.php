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
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AnthillBundle\Vacancy\Category\Resolver as CategoryResolver;
use Veslo\AnthillBundle\Vacancy\Resolver as VacancyResolver;

/**
 * Adds a vacancy to the specified category
 */
class CategoryAssigner
{
    /**
     * Extracts a vacancy instance from the specified context
     *
     * @var VacancyResolver
     */
    private $vacancyResolver;

    /**
     * Extracts a category instance from the specified context
     *
     * @var CategoryResolver
     */
    private $categoryResolver;

    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * CategoryAssigner constructor.
     *
     * @param VacancyResolver   $vacancyResolver   Extracts a vacancy instance from the specified context
     * @param CategoryResolver  $categoryResolver  Extracts a category instance from the specified context
     * @param VacancyRepository $vacancyRepository Vacancy repository
     */
    public function __construct(
        VacancyResolver $vacancyResolver,
        CategoryResolver $categoryResolver,
        VacancyRepository $vacancyRepository
    ) {
        $this->vacancyResolver   = $vacancyResolver;
        $this->categoryResolver  = $categoryResolver;
        $this->vacancyRepository = $vacancyRepository;
    }

    /**
     * Adds a vacancy to the specified category and saves a relation in the local storage
     *
     * @param Vacancy  $vacancy  Vacancy entity
     * @param Category $category Category entity
     *
     * @return void
     */
    public function assign(Vacancy $vacancy, Category $category): void
    {
        $vacancy->addCategory($category);

        $this->vacancyRepository->save($vacancy);
    }

    /**
     * Examines a parsed data from website and adds an additional category to the existent vacancy
     *
     * @param ParsedDto $scanResult Context of parsed vacancy data
     *
     * @return void
     */
    public function expandByParsedDto(ParsedDto $scanResult): void
    {
        $vacancy = $this->vacancyResolver->resolveByParsedDto($scanResult);

        if (!$vacancy instanceof Vacancy) {
            return;
        }

        $location = $scanResult->getLocation();
        $roadmap  = $location->getRoadmap();

        $category = $this->categoryResolver->resolveByRoadmap($roadmap);

        if (!$category instanceof Category) {
            return;
        }

        $this->assign($vacancy, $category);
    }
}
