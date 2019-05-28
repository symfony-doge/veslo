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

namespace Veslo\AnthillBundle\Vacancy;

use DateTime;
use Veslo\AnthillBundle\Company\Creator as CompanyCreator;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Entity\Company;
use Veslo\AnthillBundle\Entity\Repository\VacancyRepository;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AnthillBundle\Vacancy\Category\Resolver as CategoryResolver;
use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;

/**
 * Creates and persists a new vacancy instance in local storage
 */
class Creator
{
    /**
     * Creates and persists a new company in local storage
     *
     * @var CompanyCreator
     */
    private $companyCreator;

    /**
     * Extracts a category instance from the specified context
     *
     * @var CategoryResolver
     */
    private $categoryResolver;

    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * Company repository
     *
     * @var BaseEntityRepository
     */
    private $companyRepository;

    /**
     * Creator constructor.
     *
     * @param CompanyCreator       $companyCreator    Creates and persists a new company in local storage
     * @param CategoryResolver     $categoryResolver  Extracts a category instance from the specified context
     * @param VacancyRepository    $vacancyRepository Vacancy repository
     * @param BaseEntityRepository $companyRepository Company repository
     */
    public function __construct(
        CompanyCreator $companyCreator,
        CategoryResolver $categoryResolver,
        VacancyRepository $vacancyRepository,
        BaseEntityRepository $companyRepository
    ) {
        $this->companyCreator    = $companyCreator;
        $this->categoryResolver  = $categoryResolver;
        $this->vacancyRepository = $vacancyRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Creates and returns a newly vacancy entity by specified parsing context
     *
     * @param ParsedDto $scanResult Context of parsed vacancy data
     *
     * @return Vacancy
     */
    public function createByParsedDto(ParsedDto $scanResult): Vacancy
    {
        $location = $scanResult->getLocation();
        $roadmap  = $location->getRoadmap();
        $data     = $scanResult->getVacancy();

        $vacancy = new Vacancy();
        $vacancy->setRoadmapName($roadmap->getName());
        $vacancy->setExternalIdentifier($data->getExternalIdentifier());
        $vacancy->setSynchronizationDate(new DateTime());
        $vacancy->setUrl($data->getUrl());
        $vacancy->setRegionName($data->getRegionName());
        $vacancy->setTitle($data->getTitle());
        $vacancy->setSnippet($data->getSnippet());
        $vacancy->setText($data->getText());
        $vacancy->setSalaryFrom($data->getSalaryFrom());
        $vacancy->setSalaryTo($data->getSalaryTo());
        $vacancy->setSalaryCurrency($data->getSalaryCurrency());
        $vacancy->setPublicationDate($data->getPublicationDate());

        $company  = $this->resolveCompany($data);
        $category = $this->categoryResolver->resolveByRoadmap($roadmap);

        $vacancy->setCompany($company);

        if ($category instanceof Category) {
            $vacancy->addCategory($category);
        }

        $this->vacancyRepository->save($vacancy);

        return $vacancy;
    }

    /**
     * Returns related company entity if exists or calls the company creator to build a new one
     *
     * @param RawDto $vacancyData Context of raw vacancy data from website
     *
     * @return Company
     */
    private function resolveCompany(RawDto $vacancyData): Company
    {
        $companyName = $vacancyData->getCompanyName();
        $company     = $this->companyRepository->findOneByName($companyName);

        if (!$company instanceof Company) {
            $company = $this->companyCreator->createByVacancyRawDto($vacancyData, true);
        }

        return $company;
    }
}
