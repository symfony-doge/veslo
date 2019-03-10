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
     * Sets paginator for search query modification
     *
     * @param PaginatorInterface $paginator Modifies vacancy search query to provide data in small bunches
     *
     * @return void
     */
    public function setPaginator(PaginatorInterface $paginator): void;

    /**
     * Returns pagination meta-object with entity array selected by modified query
     *
     * @param CriteriaDto $criteria Pagination criteria
     *
     * @return AbstractPagination
     */
    public function getPagination(CriteriaDto $criteria): AbstractPagination;
}
