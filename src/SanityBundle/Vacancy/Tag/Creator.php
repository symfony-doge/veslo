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

namespace Veslo\SanityBundle\Vacancy\Tag;

use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\SanityBundle\Dto\Vacancy\TagDto;
use Veslo\SanityBundle\Entity\Vacancy\Tag;

/**
 * Creates and persists a new sanity tag in the local storage
 */
class Creator
{
    /**
     * Repository where sanity tag entities are stored
     *
     * @var BaseEntityRepository
     */
    private $tagRepository;

    /**
     * Creator constructor.
     *
     * @param BaseEntityRepository $tagRepository Repository where sanity tag entities are stored
     */
    public function __construct(BaseEntityRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Creates and returns a new sanity tag entity by specified data
     *
     * @param TagDto $tagData        Context of sanity tag data
     * @param bool   $isCascadeChild Whenever entity creation is just a part of the entity-owner creation and entity
     *                               manager should not be instantly flushed
     *
     * @return Tag
     */
    public function createByDto(TagDto $tagData, bool $isCascadeChild = false): Tag
    {
        $tag = new Tag();

        // TODO

        return $tag;
    }
}
