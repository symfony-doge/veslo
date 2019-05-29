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

namespace Veslo\AnthillBundle\Entity\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Cache;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AppBundle\Dto\Paginator\CriteriaDto as PaginationCriteria;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\AppBundle\Entity\Repository\PaginateableInterface;

/**
 * Vacancy repository
 */
class VacancyRepository extends BaseEntityRepository implements PaginateableInterface
{
    /**
     * A hint for the pagination building process to include a specific category match statement
     *
     * Usage example:
     * ```
     * $paginationCriteria->addHint(VacancyRepository::PAGINATION_HINT_CATEGORY, $category);
     * ```
     *
     * @const string
     */
    public const PAGINATION_HINT_CATEGORY = 'category';

    /**
     * Modifies vacancy search query to provide data in small bunches
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Options for vacancy repository, ex. page cache time
     *
     * @var array
     */
    private $options;

    /**
     * {@inheritdoc}
     */
    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }

    /**
     * Sets options for vacancy repository
     *
     * @param array $options Options for vacancy repository, ex. page cache time
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults(
            [
                'cache_result_namespace' => md5(__CLASS__),
                'cache_result_lifetime'  => 300,
            ]
        );

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * Returns vacancy by specified SEO-friendly identifier
     *
     * @param string $slug SEO-friendly vacancy identifier
     *
     * @return Vacancy|null
     */
    public function findBySlug(string $slug): ?Vacancy
    {
        $criteria = new Criteria();
        $criteria->andWhere($criteria->expr()->eq('e.slug', $slug));

        return $this->getQuery($criteria)->getOneOrNullResult();
    }

    /**
     * Returns vacancy by roadmap name and identifier on external job website
     *
     * @param string $roadmapName        Roadmap name
     * @param string $externalIdentifier Identifier on external job website
     *
     * @return Vacancy|null
     */
    public function findByRoadmapNameAndExternalIdentifier(string $roadmapName, string $externalIdentifier): ?Vacancy
    {
        $criteria = new Criteria();
        $criteria
            ->andWhere($criteria->expr()->eq('e.roadmapName', $roadmapName))
            ->andWhere($criteria->expr()->eq('e.externalIdentifier', $externalIdentifier))
        ;

        $query = $this->getQuery($criteria);

        return $query->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     *
     * Prevents exposition of database layer details to other application layers
     * All doctrine-related things (ex. criteria building) remains encapsulated
     */
    public function getPagination(PaginationCriteria $criteria): AbstractPagination
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder
            // fetch joins for caching.
            ->innerJoin('e.company', 'c')
            ->addSelect('c')
            // inner join for company is required; due to fixtures loading logic there are some cases
            // when a deletion date can be set in company and not present in related vacancies at the same time.
            // it leads to inconsistent state in test environment; normally, a soft delete logic should be
            // properly applied for all relations at once.
            ->leftJoin('e.categories', 'ct')
            ->addSelect('ct')
            ->orderBy('e.id', Criteria::DESC)
        ;

        $queryBuilder = $this->applyPaginationHints($queryBuilder, $criteria);

        list($page, $limit, $options) = [$criteria->getPage(), $criteria->getLimit(), $criteria->getOptions()];

        $query = $queryBuilder->getQuery();
        $query
            ->setCacheable(true)
            ->setCacheMode(Cache::MODE_NORMAL)
        ;

        /** @var AbstractPagination $pagination */
        $pagination = $this->paginator->paginate($query, $page, $limit, $options);

        return $pagination;
    }

    /**
     * Returns a query builder instance modified according to the pagination hints, provided by criteria
     *
     * @param QueryBuilder       $queryBuilder A query builder instance
     * @param PaginationCriteria $criteria     Pagination criteria
     *
     * @return QueryBuilder
     */
    private function applyPaginationHints(QueryBuilder $queryBuilder, PaginationCriteria $criteria): QueryBuilder
    {
        $paginationHints = $criteria->getHints();

        // Hint: category.
        if (array_key_exists(self::PAGINATION_HINT_CATEGORY, $paginationHints)) {
            /** @var Category $category */
            $category = $paginationHints[self::PAGINATION_HINT_CATEGORY];

            $queryBuilder
                ->andWhere($queryBuilder->expr()->isMemberOf(':category', 'e.categories'))
                ->setParameter(':category', $category)
            ;
        }

        return $queryBuilder;
    }
}
