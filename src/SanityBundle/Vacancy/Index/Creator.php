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

namespace Veslo\SanityBundle\Vacancy\Index;

use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\SanityBundle\Dto\Vacancy\IndexDto;
use Veslo\SanityBundle\Dto\Vacancy\TagDto;
use Veslo\SanityBundle\Entity\Vacancy\Index;
use Veslo\SanityBundle\Entity\Vacancy\Tag;
use Veslo\SanityBundle\Vacancy\Tag\Creator as TagCreator;

/**
 * Creates and persists sanity index entities in the local storage
 */
class Creator
{
    /**
     * Creates and persists sanity tags in the local storage
     *
     * @var TagCreator
     */
    private $tagCreator;

    /**
     * Repository where sanity index entities are stored
     *
     * @var BaseEntityRepository
     */
    private $indexRepository;

    /**
     * Repository where sanity tag entities are stored
     *
     * @var BaseEntityRepository
     */
    private $tagRepository;

    /**
     * Creator constructor.
     *
     * @param TagCreator           $tagCreator      Creates and persists sanity tags in the local storage
     * @param BaseEntityRepository $indexRepository Repository where sanity index entities are stored
     * @param BaseEntityRepository $tagRepository   Repository where sanity tag entities are stored
     */
    public function __construct(
        TagCreator $tagCreator,
        BaseEntityRepository $indexRepository,
        BaseEntityRepository $tagRepository
    ) {
        $this->tagCreator      = $tagCreator;
        $this->indexRepository = $indexRepository;
        $this->tagRepository   = $tagRepository;
    }

    /**
     * Creates and returns a new sanity index entity by specified data
     *
     * @param IndexDto $indexData      Context of sanity index data
     * @param bool     $isCascadeChild Whenever entity creation is just a part of the entity-owner creation
     *                                 and entity manager should not be instantly flushed
     *
     * @return Index
     */
    public function createByDto(IndexDto $indexData, bool $isCascadeChild = false): Index
    {
        $index = new Index();

        $indexValue = $indexData->getValue();
        $index->setValue($indexValue);

        $vacancyId = $indexData->getVacancyId();
        $index->setVacancyId($vacancyId);

        $indexationDate = $indexData->getIndexationDate();
        $index->setIndexationDate($indexationDate);

        $tagDataArray = $indexData->getTags();
        $tags         = $this->resolveTags($tagDataArray);

        foreach ($tags as $tag) {
            $index->addTag($tag);
        }

        $this->indexRepository->save($index, !$isCascadeChild);

        return $index;
    }

    /**
     * Returns related tag entities
     *
     * @param TagDto[] $tagDataArray Tags to be assigned to the vacancy after indexation
     *
     * @return Tag[]
     */
    private function resolveTags(array $tagDataArray): array
    {
        $tags = [];

        foreach ($tagDataArray as $tagData) {
            $tags[] = $this->resolveTag($tagData);
        }

        return $tags;
    }

    /**
     * Returns related tag entity if exists or calls the tag creator to build a new set
     *
     * @param TagDto $tagData Tag to be assigned to the vacancy
     *
     * @return Tag
     */
    private function resolveTag(TagDto $tagData): Tag
    {
        $tagName = $tagData->getName();
        $tag     = $this->tagRepository->findOneByName($tagName);

        if (!$tag instanceof Tag) {
            $tag = $this->tagCreator->createByDto($tagData, true);
        }

        return $tag;
    }
}
