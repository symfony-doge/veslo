<?php

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
