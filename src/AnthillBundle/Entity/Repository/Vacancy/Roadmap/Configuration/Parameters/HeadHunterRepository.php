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

namespace Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters;

use Doctrine\ORM\Cache;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AnthillBundle\Exception\Vacancy\Roadmap\ConfigurationNotFoundException;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;

/**
 * Repository for HeadHunter vacancy searching parameters
 */
class HeadHunterRepository extends BaseEntityRepository
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
