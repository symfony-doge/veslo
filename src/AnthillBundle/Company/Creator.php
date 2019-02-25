<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Company;

use DateTime;
use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Entity\Company;
use Veslo\AppBundle\Entity\Repository\BaseRepository;

/**
 * Creates and persists a new company in local storage
 */
class Creator
{
    /**
     * Company repository
     *
     * @var BaseRepository
     */
    private $companyRepository;

    /**
     * Creator constructor.
     *
     * @param BaseRepository $companyRepository Company repository
     */
    public function __construct(BaseRepository $companyRepository)
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
