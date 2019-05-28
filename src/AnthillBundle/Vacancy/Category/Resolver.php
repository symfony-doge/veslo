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

namespace Veslo\AnthillBundle\Vacancy\Category;

use Veslo\AnthillBundle\Dto\Vacancy\ConfigurableRoadmapDto;
use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;

/**
 * Extracts a category instance from the specified context
 */
class Resolver
{
    /**
     * Creates and persists a new category for vacancies in local storage
     *
     * @var Creator
     */
    private $categoryCreator;

    /**
     * Vacancy category repository
     *
     * @var BaseEntityRepository
     */
    private $categoryRepository;

    /**
     * Resolver constructor.
     *
     * @param Creator              $categoryCreator    Creates and persists a new category for vacancies
     * @param BaseEntityRepository $categoryRepository Vacancy category repository
     */
    public function __construct(Creator $categoryCreator, BaseEntityRepository $categoryRepository)
    {
        $this->categoryCreator    = $categoryCreator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns related category entity if exists or calls the category creator to build a new one by roadmap
     *
     * @param RoadmapDto $roadmap Context of roadmap by which the vacancy was found
     *
     * @return Category|null
     */
    public function resolveByRoadmap(RoadmapDto $roadmap): ?Category
    {
        if (!$roadmap instanceof ConfigurableRoadmapDto) {
            return null;
        }

        $configuration = $roadmap->getConfiguration();
        $categoryName  = $configuration->getKey();
        $category      = $this->categoryRepository->findOneByName($categoryName);

        if (!$category instanceof Category) {
            $category = $this->categoryCreator->createByRoadmapConfigurationDto($configuration, true);
        }

        return $category;
    }
}
