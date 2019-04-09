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

use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;

/**
 * Creates and persists a new category for vacancies in local storage
 */
class Creator
{
    /**
     * Vacancy category repository
     *
     * @var BaseEntityRepository
     */
    private $categoryRepository;

    /**
     * Creator constructor.
     *
     * @param BaseEntityRepository $categoryRepository Vacancy category repository
     */
    public function __construct(BaseEntityRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Creates and returns a new category entity according to specified roadmap configuration
     *
     * @param ConfigurationDto $configuration  Context of configuration for search algorithm used by roadmap
     * @param bool             $isCascadeChild Whenever entity creation is a part of the entity-owner creation and
     *                                         entity manager should not be instantly flushed (for transaction purposes)
     *
     * @return Category
     */
    public function createByRoadmapConfigurationDto(
        ConfigurationDto $configuration,
        bool $isCascadeChild = false
    ): Category {
        $category = new Category();

        $categoryName = $configuration->getKey();
        $category->setName($categoryName);

        $this->categoryRepository->save($category, !$isCascadeChild);

        return $category;
    }
}
