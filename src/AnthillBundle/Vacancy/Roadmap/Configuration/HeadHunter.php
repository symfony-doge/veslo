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

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Configuration;

use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters\HeadHunterRepository as HeadHunterParametersRepository;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AnthillBundle\Exception\Vacancy\Roadmap\ConfigurationNotFoundException;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;

/**
 * Represents configuration of vacancy search algorithms for HeadHunter
 */
class HeadHunter implements ConfigurationInterface
{
    /**
     * Converts an object into a set of arrays/scalars
     *
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Repository for HeadHunter search parameters
     *
     * @var HeadHunterParametersRepository
     */
    private $parametersRepository;

    /**
     * Parameters for vacancy search on HeadHunter website
     *
     * @var HeadHunterParameters
     */
    private $_parameters;

    /**
     * HeadHunter constructor.
     *
     * @param NormalizerInterface            $normalizer           Converts an object into a set of arrays/scalars
     * @param LoggerInterface                $logger               Logger as it is
     * @param HeadHunterParametersRepository $parametersRepository Repository for HeadHunter search parameters
     */
    public function __construct(
        NormalizerInterface $normalizer,
        LoggerInterface $logger,
        HeadHunterParametersRepository $parametersRepository
    ) {
        $this->normalizer           = $normalizer;
        $this->logger               = $logger;
        $this->parametersRepository = $parametersRepository;
        $this->_parameters          = null;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(string $configurationKey): void
    {
        $this->_parameters = $this->parametersRepository->requireByConfigurationKey($configurationKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): ParametersInterface
    {
        $this->ensureParameters();

        return $this->_parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(ParametersInterface $parameters): void
    {
        $this->_parameters = $parameters;

        $this->ensureParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function save(): void
    {
        $this->ensureParameters();

        $this->parametersRepository->save($this->_parameters);

        $normalizedParameters = $this->normalizer->normalize($this->_parameters);
        $this->logger->debug('Roadmap configuration changed.', ['parameters' => $normalizedParameters]);
    }

    /**
     * Throws exception if configuration parameters have not been applied
     *
     * @return void
     */
    private function ensureParameters(): void
    {
        if (!$this->_parameters instanceof HeadHunterParameters) {
            throw new ConfigurationNotFoundException();
        }
    }
}
