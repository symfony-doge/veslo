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
use Veslo\AnthillBundle\Dto\Vacancy\Collector\AcceptanceDto;

/**
 * Converts vacancy acceptance context object into a set of arrays/scalars
 */
class AcceptanceNormalizer implements NormalizerInterface
{
    /**
     * Converts an vacancy scan result object into a set of arrays/scalars
     *
     * @var ScanResultNormalizer
     */
    private $normalizer;

    /**
     * AcceptanceNormalizer constructor.
     *
     * @param ScanResultNormalizer $normalizer Converts an vacancy scan result object into a set of arrays/scalars
     */
    public function __construct(ScanResultNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     *
     * @var AcceptanceDto $object Context of vacancy data that has been accepted for persisting and research
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $scanResult           = $object->getData();
        $scanResultNormalized = $this->normalizer->normalize($scanResult, $format, $context);

        $conditions           = $object->getConditions(); // iterable
        $conditionsNormalized = [];

        foreach ($conditions as $condition) {
            $conditionsNormalized[] = $condition /** ->getDescription() */;
        }

        $acceptanceNormalized = array_merge(['conditions' => $conditionsNormalized], $scanResultNormalized);

        return $acceptanceNormalized;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof AcceptanceDto;
    }
}
