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

namespace Veslo\AnthillBundle\Menu\Vacancy;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;

/**
 * Builder for vacancy categories menu
 */
class CategoriesBuilder
{
    /**
     * Factory to create menu items
     *
     * @var FactoryInterface
     */
    private $menuFactory;

    /**
     * Repository with vacancy categories
     *
     * @var BaseEntityRepository
     */
    private $categoryRepository;

    /**
     * Routes to the vacancy list by category actions
     *
     * First route from array will be used as a "main" route for URI building, all the rest to make an item "active"
     * on additional pages
     *
     * @var array
     */
    private $routes;

    /**
     * CategoriesBuilder constructor.
     *
     * @param FactoryInterface     $menuFactory        Factory to create items
     * @param BaseEntityRepository $categoryRepository Repository with vacancy categories
     * @param array                $routes             Routes to the vacancy list by category actions
     */
    public function __construct(FactoryInterface $menuFactory, BaseEntityRepository $categoryRepository, array $routes)
    {
        $this->menuFactory        = $menuFactory;
        $this->categoryRepository = $categoryRepository;
        $this->routes             = $routes;
    }

    /**
     * Returns root item for vacancy categories menu rendering
     *
     * @return ItemInterface
     */
    public function createVacancyCategoriesMenu(): ItemInterface
    {
        $root = $this->menuFactory->createItem('root');

        /** @var Category[] $categories */
        $categories = $this->categoryRepository->findAll();

        foreach ($categories as $category) {
            $root = $this->appendTo($root, $category);
        }

        return $root;
    }

    /**
     * Appends a vacancy category as an item to the root of menu tree
     *
     * @param ItemInterface $root     Menu root item
     * @param Category      $category A category instance
     *
     * @return ItemInterface
     */
    private function appendTo(ItemInterface $root, Category $category): ItemInterface
    {
        $categoryName = $category->getName();

        $categoryMenuItemOptions = [
            'label'           => 'menu_item_category',
            'route'           => $this->routes[0],
            'routeParameters' => [
                'categoryName' => $categoryName,
            ],
        ];

        $routesMatch  = array_slice($this->routes, 1);
        $extrasRoutes = [];

        foreach ($routesMatch as $routeMatch) {
            $extrasRoutes[] = [
                'route'      => $routeMatch,
                'parameters' => [
                    'categoryName' => $categoryName,
                ],
            ];
        }

        $categoryMenuItemOptions = array_merge(
            $categoryMenuItemOptions,
            [
                'extras' => [
                    'routes'             => $extrasRoutes,
                    'translation_params' => [
                        '%categoryName%' => $categoryName,
                    ],
                    'translation_domain' => 'menu',
                ],
            ]
        );

        $root->addChild($categoryName, $categoryMenuItemOptions);

        return $root;
    }
}
