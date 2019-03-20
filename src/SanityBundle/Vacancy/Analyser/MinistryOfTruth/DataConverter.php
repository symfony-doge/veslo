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

namespace Veslo\SanityBundle\Vacancy\Analyser\MinistryOfTruth;

use SymfonyDoge\MinistryOfTruthClient\Dto\Response\Index\ContentDto as IndexData;
use Veslo\SanityBundle\Dto\Vacancy\IndexDto;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;
use SymfonyDoge\MinistryOfTruthClient\Dto\Response\Index\TagDto as TagData;
use Veslo\SanityBundle\Dto\Vacancy\TagDto;

/**
 * Converts data from format of external microservice to local data transfer objects
 */
class DataConverter
{
    /**
     * Returns index dto created by external data source
     *
     * @param IndexData $index Sanity index content structure from external microservice
     *
     * @return IndexDto
     */
    public function convertIndex(IndexData $index): IndexDto
    {
        $indexDto = new IndexDto();

        $indexValue = $index->getValue();
        $indexDto->setValue($indexValue);

        $tags        = $index->getTags();
        $tagDtoArray = $this->convertTags($tags);

        foreach ($tagDtoArray as $tagDto) {
            $indexDto->addTag($tagDto);
        }

        return $indexDto;
    }

    /**
     * Returns tags dto array created by external data source
     *
     * @param array $tagData Tags array, indexed by group name
     *
     * @return array
     */
    private function convertTags(array $tagData): array
    {
        $tagDtoArray = [];

        /** @var TagData[] $tags */
        foreach ($tagData as $tagsGroupName => $tags) {
            $groupDto = new GroupDto();
            $groupDto->setName($tagsGroupName);

            foreach ($tags as $tag) {
                $tagDto = new TagDto();
                $tagDto->setGroup($groupDto);

                $tagName = $tag->getName();
                $tagDto->setName($tagName);

                $tagTitle = $tag->getTitle();
                $tagDto->setTitle($tagTitle);

                $tagDescription = $tag->getDescription();
                $tagDto->setDescription($tagDescription);

                $tagColor = $tag->getColor();
                $tagDto->setColor($tagColor);

                $tagImageUrl = $tag->getImageUrl();
                $tagDto->setImageUrl($tagImageUrl);

                $tagDtoArray[] = $tagDto;
            }
        }

        return $tagDtoArray;
    }
}
