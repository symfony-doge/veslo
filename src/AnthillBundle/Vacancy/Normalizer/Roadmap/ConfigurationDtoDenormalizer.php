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
