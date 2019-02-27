<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Provider;

use Knp\Component\Pager\Pagination\AbstractPagination;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AppBundle\Dto\Paginator\CriteriaDto;
use Veslo\AppBundle\Entity\Repository\PaginateableInterface;

/**
 * Provides vacancies by a simple concept of journal with pages, using pagination internally
 */
class Journal
{
    /**
     * Vacancy repository
     *
     * @var PaginateableInterface
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
     * @param PaginateableInterface $vacancyRepository Vacancy repository
     * @param array                 $options           Options for vacancy provider, ex. number per page
     */
    public function __construct(PaginateableInterface $vacancyRepository, array $options)
    {
        $this->vacancyRepository = $vacancyRepository;

        // For readability purposes.
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'per_page' => 10,
        ]);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * Returns all vacancies on specified page from newest(1) to oldest(N)
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

        $pagination = $this->vacancyRepository->getPagination($paginationCriteria);

        return $pagination;
    }
}