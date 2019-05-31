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

namespace Veslo\AnthillBundle\Vacancy\Provider;

use DateTime;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veslo\AnthillBundle\Entity\Repository\VacancyRepository;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Entity\Vacancy\Category;
use Veslo\AnthillBundle\Enum\Route;
use Veslo\AppBundle\Dto\Paginator\CriteriaDto;

/**
 * Provides non-archived vacancies by a simple concept of journal with pages, using pagination internally
 */
class Journal
{
    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * Options for vacancy provider, ex. number per page
     *
     * @var array
     */
    private $options;

    /**
     * Journal constructor.
     *
     * @param VacancyRepository $vacancyRepository Vacancy repository
     * @param array             $options           Options for vacancy provider, ex. number per page
     */
    public function __construct(VacancyRepository $vacancyRepository, array $options)
    {
        $this->vacancyRepository = $vacancyRepository;

        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults(
            [
                'per_page'                   => 10,
                'max_days_after_publication' => 30,
            ]
        );
        $optionsResolver->setAllowedTypes('per_page', ['int']);
        $optionsResolver->setAllowedTypes('max_days_after_publication', ['int']);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * Returns all vacancies on the specified page from newest(1) to oldest(N)
     *
     * @param int $page Page for pagination
     *
     * @return AbstractPagination<Vacancy>
     */
    public function read(int $page = 1): AbstractPagination
    {
        $paginationCriteria = $this->buildCommonPaginationCriteria($page);

        $pagination = $this->vacancyRepository->getPagination($paginationCriteria);
        $pagination->setUsedRoute(Route::VACANCY_LIST_PAGE);

        return $pagination;
    }

    /**
     * Returns all vacancies on the specified page from newest(1) to oldest(N) within the target category
     *
     * @param Category $category A vacancy category instance
     * @param int      $page     Page for pagination
     *
     * @return AbstractPagination<Vacancy>
     */
    public function readCategory(Category $category, int $page = 1): AbstractPagination
    {
        $paginationCriteria = $this->buildCommonPaginationCriteria($page);
        $paginationCriteria->addHint(VacancyRepository::PAGINATION_HINT_CATEGORY, $category);

        $pagination = $this->vacancyRepository->getPagination($paginationCriteria);
        $pagination->setUsedRoute(Route::VACANCY_LIST_BY_CATEGORY_PAGE);

        return $pagination;
    }

    /**
     * Returns a criteria instance with common parameters for pagination building
     *
     * @param int $page Page for pagination
     *
     * @return CriteriaDto
     */
    private function buildCommonPaginationCriteria(int $page = 1): CriteriaDto
    {
        $pageNormalized = max(1, $page);

        $paginationCriteria = new CriteriaDto();
        $paginationCriteria->setPage($pageNormalized);
        $paginationCriteria->setLimit($this->options['per_page']);

        $daysFrom = $this->options['max_days_after_publication'];
        $dateFrom = new DateTime("-$daysFrom days");
        $paginationCriteria->addHint(VacancyRepository::PAGINATION_HINT_SYNC_DATE_AFTER, $dateFrom);

        return $paginationCriteria;
    }
}
