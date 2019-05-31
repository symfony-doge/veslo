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
use Veslo\AnthillBundle\Enum\Route;
use Veslo\AppBundle\Dto\Paginator\CriteriaDto;

/**
 * Provides archived vacancies, uses pagination internally
 */
class Archive
{
    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * Options for vacancy provider, e.g. number per page
     *
     * @var array
     */
    private $options;

    /**
     * Archive constructor.
     *
     * @param VacancyRepository $vacancyRepository Vacancy repository
     * @param array             $options           Options for vacancy provider
     */
    public function __construct(VacancyRepository $vacancyRepository, array $options)
    {
        $this->vacancyRepository = $vacancyRepository;

        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults(
            [
                'per_page'                   => 10,
                'min_days_after_publication' => 30,
            ]
        );
        $optionsResolver->setAllowedTypes('per_page', ['int']);
        $optionsResolver->setAllowedTypes('min_days_after_publication', ['int']);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * Returns all vacancies on the specified archive page from newest(1) to oldest(N)
     *
     * @param int $page Page for pagination
     *
     * @return AbstractPagination<Vacancy>
     */
    public function read(int $page = 1): AbstractPagination
    {
        $pageNormalized = max(1, $page);

        $paginationCriteria = new CriteriaDto();
        $paginationCriteria->setPage($pageNormalized);
        $paginationCriteria->setLimit($this->options['per_page']);

        $daysFrom = $this->options['min_days_after_publication'];
        $dateFrom = new DateTime("-$daysFrom days");
        $paginationCriteria->addHint(VacancyRepository::PAGINATION_HINT_SYNC_DATE_BEFORE, $dateFrom);

        $pagination = $this->vacancyRepository->getPagination($paginationCriteria);
        $pagination->setUsedRoute(Route::VACANCY_ARCHIVE_PAGE);

        return $pagination;
    }
}
