<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Dto\Paginator;

use Knp\Component\Pager\PaginatorInterface;

/**
 * Pagination criteria
 *
 * @see PaginatorInterface
 */
class CriteriaDto
{
    /**
     * Page number
     *
     * @var int|null
     */
    private $page;

    /**
     * Number of items per page
     *
     * @var int|null
     */
    private $limit;

    /**
     * Additional options for pagination
     *
     * @var array
     */
    private $options = [];

    /**
     * Returns page number
     *
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Sets page number
     *
     * @param int|null $page Page number
     *
     * @return void
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * Returns number of items per page
     *
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Sets number of items per page
     *
     * @param int|null $limit Number of items per page
     *
     * @return void
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * Returns additional options for pagination
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Sets additional options for pagination
     *
     * @param array $options Additional options for pagination
     *
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
