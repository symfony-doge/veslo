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

namespace Veslo\AnthillBundle\Vacancy\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Converts an vacancy scan result object into a set of arrays/scalars with long text truncation
 */
class ScanResultNormalizer implements NormalizerInterface
{
    /**
     * Converts an object into a set of arrays/scalars
     *
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * RawDataPreviewNormalizer constructor.
     *
     * @param NormalizerInterface $normalizer Converts an object into a set of arrays/scalars
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $normalizedData = $this->normalizer->normalize($object, $format, $context);

        $normalizedData['vacancy']['snippet'] = '...';
        $normalizedData['vacancy']['text']    = '...';

        return $normalizedData;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->normalizer->supportsNormalization($data, $format);
    }
}
