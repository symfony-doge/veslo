<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Cache;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
        $optionsResolver->setDefaults([
            'cache_result_namespace' => 'veslo.anthill.vacancy_repository.',
            'cache_result_lifetime'  => 300,
        ]);

        $this->options = $optionsResolver->resolve($options);
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
     * Prevents exposition of database layer details to other domains
     * All doctrine-related things (ex. criteria building) remains encapsulated
     */
    public function getPagination(PaginationCriteria $criteria): AbstractPagination
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
            ->useResultCache(
                true,
                $this->options['cache_result_lifetime'],
                $this->options['cache_result_namespace'] . $page
            )
        ;

        /** @var AbstractPagination $pagination */
        $pagination = $this->paginator->paginate($query, $page, $limit, $options);

        return $pagination;
    }
}
