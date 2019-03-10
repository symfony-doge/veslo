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
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\StrategyDto;

/**
 * Converts an array of scalars to valid roadmap StrategyDto instance
 */
class StrategyDtoDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!array_key_exists(StrategyDto::PROPERTY_NAME, $data)) {
            throw new UnexpectedValueException('Value for roadmap strategy name property is not exists for hydration.');
        }

        $strategyDto = new StrategyDto();
        $strategyDto->setName($data[StrategyDto::PROPERTY_NAME]);

        return $strategyDto;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return StrategyDto::class === $type;
    }
}
