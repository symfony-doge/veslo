<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Cache;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Knp\Component\Pager\PaginatorInterface;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AppBundle\Dto\Paginator\CriteriaDto as PaginationCriteria;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\AppBundle\Entity\Repository\PaginateableInterface;

/**
 * Vacancy repository
 */
class VacancyRepository extends BaseEntityRepository implements PaginateableInterface
{
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
     * Prevents exposition of database layer details to other domains
     * All doctrine-related things (ex. criteria building) remains encapsulated
     */
    public function getPaginatedResult(PaginatorInterface $paginator, PaginationCriteria $criteria): AbstractPagination
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder
            ->select('e', 'c')
            // fetch join for caching.
            ->innerJoin('e.categories', 'c')
            ->orderBy('e.id', Criteria::DESC)
        ;

        list($page, $limit, $options) = [$criteria->getPage(), $criteria->getLimit(), $criteria->getOptions()];

        $query = $queryBuilder->getQuery();
        $query
            ->setCacheable(true)
            ->setCacheMode(Cache::MODE_NORMAL)
            // TODO: load values from config. (mb same value as lifetime of vacancies cache region)
            ->useResultCache(true, 300, 'veslo.anthill.vacancy_repository.page-' . $page)
        ;

        /** @var AbstractPagination $pagination */
        $pagination = $paginator->paginate($query, $page, $limit, $options);

        return $pagination;
    }
}
