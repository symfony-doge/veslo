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

namespace Veslo\SanityBundle\Vacancy\Index\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\SanityBundle\Entity\Vacancy\Index;

/**
 * Converts sanity index entity into a set of arrays/scalars for preview actions (e.g. logging)
 */
class PreviewNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @var Index $object A sanity index entity
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $indexValueNormalized = $object->getValue();

        $tags           = $object->getTags();
        $tagsNormalized = [];

        foreach ($tags as $tag) {
            $tagName        = $tag->getName();
            $tagDescription = $tag->getDescription();

            $tagsNormalized[] = ['name' => $tagName, 'description' => $tagDescription];
        }

        return [
            'value' => $indexValueNormalized,
            'tags'  => $tagsNormalized,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Index;
    }
}
