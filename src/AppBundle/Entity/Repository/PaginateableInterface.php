<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Entity\Repository;

use Knp\Component\Pager\Pagination\AbstractPagination;
use Knp\Component\Pager\PaginatorInterface;
use Veslo\AppBundle\Dto\Paginator\CriteriaDto;

/**
 * Should be implemented by repository that supports pagination
 */
interface PaginateableInterface
{
    /**
     * Returns pagination meta-object with entity array selected by modified query
     *
     * @param PaginatorInterface $paginator Modifies search query to provide data in small bunches
     * @param CriteriaDto        $criteria  Pagination criteria
     *
     * @return AbstractPagination
     */
    public function getPaginatedResult(PaginatorInterface $paginator, CriteriaDto $criteria): AbstractPagination;
}
