<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters;

use Doctrine\ORM\Cache;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AnthillBundle\Exception\Roadmap\ConfigurationNotFoundException;
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
     * @throws NonUniqueResultException
     * @throws ConfigurationNotFoundException
     */
    public function requireByConfigurationKey(string $configurationKey): HeadHunterParameters
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('p.configurationKey', ':configurationKey'))
            ->setParameter('configurationKey', $configurationKey)
            ->setCacheMode(Cache::MODE_NORMAL)
            ->setCacheable(true)
        ;

        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            throw ConfigurationNotFoundException::withKey($configurationKey);
        }
    }
}
