<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Repository;

use Doctrine\ORM\Cache;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AppBundle\Entity\Repository\BaseRepository;

/**
 * Vacancy repository
 */
class VacancyRepository extends BaseRepository
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
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('v.roadmapName', ':roadmapName'))
            ->andWhere($queryBuilder->expr()->eq('v.externalIdentifier', ':externalIdentifier'))
            ->setParameter('roadmapName', $roadmapName)
            ->setParameter('externalIdentifier', $externalIdentifier)
            ->setCacheable(true)
            ->setCacheMode(Cache::MODE_NORMAL)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
