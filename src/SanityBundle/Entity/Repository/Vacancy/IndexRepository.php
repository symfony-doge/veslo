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

namespace Veslo\SanityBundle\Entity\Repository\Vacancy;

use Doctrine\ORM\Cache;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\SanityBundle\Entity\Vacancy\Index;

/**
 * Sanity index repository
 */
class IndexRepository extends BaseEntityRepository
{
    /**
     * Returns a sanity index instance with all related data for the specified vacancy identifier
     *
     * @param int $vacancyId Vacancy identifier
     *
     * @return Index|null
     */
    public function findByVacancyId(int $vacancyId): ?Index
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder
            // fetch join for caching.
            ->leftJoin('e.tags', 't')
            ->addSelect('t')
            ->andWhere($queryBuilder->expr()->eq('e.vacancyId', ':vacancyId'))
            ->setParameter('vacancyId', $vacancyId)
        ;

        $query = $queryBuilder->getQuery();
        $query
            ->setCacheable(true)
            ->setCacheMode(Cache::MODE_NORMAL)
            ->useResultCache(true)
        ;

        return $query->getOneOrNullResult();
    }
}
