<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Normalizer\Roadmap;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use UnexpectedValueException;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;

/**
 * Converts an array of scalars to valid roadmap ConfigurationDto instance
 */
class ConfigurationDtoDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!array_key_exists(ConfigurationDto::PROPERTY_KEY, $data)) {
            throw new UnexpectedValueException(
                'Value for roadmap configuration key property is not exists for hydration.'
            );
        }

        $configurationDto = new ConfigurationDto();
        $configurationDto->setKey($data[ConfigurationDto::PROPERTY_KEY]);

        return $configurationDto;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return ConfigurationDto::class === $type;
    }
}
