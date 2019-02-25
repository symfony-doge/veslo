<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

use DateTime;
use Veslo\AnthillBundle\Dto\Vacancy\ConfigurableRoadmapDto;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;
use Veslo\AnthillBundle\Entity\Company;
use Veslo\AnthillBundle\Entity\Repository\VacancyRepository;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AppBundle\Entity\Repository\BaseRepository;
use Veslo\AnthillBundle\Company\Creator as CompanyCreator;
use Veslo\AnthillBundle\Vacancy\Category\Creator as CategoryCreator;

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
     * Creates and persists a new category for vacancies in local storage
     *
     * @var CategoryCreator
     */
    private $categoryCreator;

    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * Company repository
     *
     * @var BaseRepository
     */
    private $companyRepository;

    /**
     * Vacancy category repository
     *
     * @var BaseRepository
     */
    private $categoryRepository;

    /**
     * Creator constructor.
     *
     * @param CompanyCreator    $companyCreator     Creates and persists a new company in local storage
     * @param CategoryCreator   $categoryCreator    Creates and persists a new category for vacancies in local storage
     * @param VacancyRepository $vacancyRepository  Vacancy repository
     * @param BaseRepository    $companyRepository  Company repository
     * @param BaseRepository    $categoryRepository Vacancy category repository
     */
    public function __construct(
        CompanyCreator $companyCreator,
        CategoryCreator $categoryCreator,
        VacancyRepository $vacancyRepository,
        BaseRepository $companyRepository,
        BaseRepository $categoryRepository
    ) {
        $this->vacancyRepository  = $vacancyRepository;
        $this->companyCreator     = $companyCreator;
        $this->categoryCreator    = $categoryCreator;
        $this->companyRepository  = $companyRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Creates and returns a new vacancy entity by specified parsing context
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

        $company  = $this->createCompany($data);
        $category = $this->createCategory($roadmap);

        $vacancy->setCompany($company);

        if ($category instanceof Category) {
            $vacancy->addCategory($category);
        }

        $this->vacancyRepository->save($vacancy);

        return $vacancy;
    }

    /**
     * Creates and returns a company instance by specified raw vacancy data
     *
     * @param RawDto $vacancyData Context of raw vacancy data from website
     *
     * @return Company
     */
    private function createCompany(RawDto $vacancyData): Company
    {
        $companyName = $vacancyData->getCompanyName();
        $company     = $this->companyRepository->findOneByName($companyName);

        if (!$company instanceof Company) {
            $company = $this->companyCreator->createByVacancyRawDto($vacancyData, true);
        }

        return $company;
    }

    /**
     * Creates and returns a category instance by roadmap
     *
     * @param RoadmapDto $roadmap Context of roadmap by which the vacancy was found
     *
     * @return Category|null
     */
    private function createCategory(RoadmapDto $roadmap): ?Category
    {
        if (!$roadmap instanceof ConfigurableRoadmapDto) {
            return null;
        }

        $configuration = $roadmap->getConfiguration();
        $categoryName  = $configuration->getKey();
        $category      = $this->categoryRepository->findOneByName($categoryName);

        if (!$category instanceof Category) {
            $category = $this->categoryCreator->createByRoadmapConfigurationDto($configuration, true);
        }

        return $category;
    }
}
