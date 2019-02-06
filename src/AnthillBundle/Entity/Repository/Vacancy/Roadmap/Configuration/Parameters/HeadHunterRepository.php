<?php

namespace Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AppBundle\Entity\Repository\BaseRepository;

/**
 * Repository for HeadHunter vacancy searching parameters
 */
class HeadHunterRepository extends BaseRepository
{
    /**
     * Returns vacancy searching parameters for HeadHunter website by roadmap configuration key
     *
     * @param string $configurationKey Roadmap configuration key
     *
     * @return HeadHunterParameters
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function requireByConfigurationKey(string $configurationKey): HeadHunterParameters
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('p.configurationKey', ':configurationKey'))
            ->setParameter('configurationKey', $configurationKey)
        ;

        return $queryBuilder->getQuery()->getSingleResult();
    }
}
