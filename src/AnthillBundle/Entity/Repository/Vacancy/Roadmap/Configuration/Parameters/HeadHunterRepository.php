<?php

namespace Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters;

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
     */
    public function requireByConfigurationKey(string $configurationKey): HeadHunterParameters
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        // TODO
    }
}
