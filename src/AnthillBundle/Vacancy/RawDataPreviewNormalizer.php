<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Converts an vacancy raw data object into a set of arrays/scalars with long text truncation
 */
class RawDataPreviewNormalizer implements NormalizerInterface
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
