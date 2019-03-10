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

namespace Veslo\AnthillBundle\Company;

use DateTime;
use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Entity\Company;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;

/**
 * Creates and persists a new company in local storage
 */
class Creator
{
    /**
     * Company repository
     *
     * @var BaseEntityRepository
     */
    private $companyRepository;

    /**
     * Creator constructor.
     *
     * @param BaseEntityRepository $companyRepository Company repository
     */
    public function __construct(BaseEntityRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Creates and returns a new company entity by specified vacancy data
     *
     * @param RawDto $vacancy        Context of raw vacancy data from website
     * @param bool   $isCascadeChild Whenever entity creation is a part of the entity-owner creation and entity manager
     *                               should not be instantly flushed (for transaction purposes)
     *
     * @return Company
     */
    public function createByVacancyRawDto(RawDto $vacancy, bool $isCascadeChild = false): Company
    {
        $company = new Company();

        $company->setName($vacancy->getCompanyName());
        $company->setUrl($vacancy->getCompanyUrl());
        $company->setLogoUrl($vacancy->getCompanyLogoUrl());
        $company->setSynchronizationDate(new DateTime());

        $this->companyRepository->save($company, !$isCascadeChild);

        return $company;
    }
}
