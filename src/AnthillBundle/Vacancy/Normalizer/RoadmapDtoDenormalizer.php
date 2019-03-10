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

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use UnexpectedValueException;
use Veslo\AnthillBundle\Dto\Vacancy\ConfigurableRoadmapDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\StrategyDto;
use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;

/**
 * Converts an array of scalars to valid RoadmapDto instance or to any suitable child instance
 */
class RoadmapDtoDenormalizer implements DenormalizerInterface
{
    /**
     * Converts an array of scalars to valid roadmap StrategyDto instance
     *
     * @var DenormalizerInterface
     */
    private $strategyDenormalizer;

    /**
     * Converts an array of scalars to valid roadmap ConfigurationDto instance
     *
     * @var DenormalizerInterface
     */
    private $configurationDenormalizer;

    /**
     * RoadmapDtoDenormalizer constructor.
     *
     * @param DenormalizerInterface $strategyDenormalizer      Converts an array of scalars to StrategyDto instance
     * @param DenormalizerInterface $configurationDenormalizer Converts an array of scalars to ConfigurationDto instance
     */
    public function __construct(
        DenormalizerInterface $strategyDenormalizer,
        DenormalizerInterface $configurationDenormalizer
    ) {
        $this->strategyDenormalizer      = $strategyDenormalizer;
        $this->configurationDenormalizer = $configurationDenormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (array_key_exists(ConfigurableRoadmapDto::PROPERTY_STRATEGY, $data)
            && array_key_exists(ConfigurableRoadmapDto::PROPERTY_CONFIGURATION, $data)
        ) {
            $roadmapDto = new ConfigurableRoadmapDto();

            $strategyData = $data[ConfigurableRoadmapDto::PROPERTY_STRATEGY];
            $strategyDto  = $this->strategyDenormalizer->denormalize($strategyData, StrategyDto::class);
            $roadmapDto->setStrategy($strategyDto);

            $configurationData = $data[ConfigurableRoadmapDto::PROPERTY_CONFIGURATION];
            $configurationDto  = $this->configurationDenormalizer->denormalize(
                $configurationData,
                ConfigurationDto::class
            );
            $roadmapDto->setConfiguration($configurationDto);
        } else {
            $roadmapDto = new RoadmapDto();
        }

        if (!array_key_exists(RoadmapDto::PROPERTY_NAME, $data)) {
            throw new UnexpectedValueException('Value for roadmap name property is not exists for hydration.');
        }

        $roadmapDto->setName($data[RoadmapDto::PROPERTY_NAME]);

        return $roadmapDto;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return RoadmapDto::class === $type;
    }
}
