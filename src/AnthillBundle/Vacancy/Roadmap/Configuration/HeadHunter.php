<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Configuration;

use Veslo\AnthillBundle\Entity\Repository\Vacancy\Roadmap\Configuration\Parameters\HeadHunterRepository as HeadHunterParametersRepository;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AnthillBundle\Exception\RoadmapConfigurationNotFoundException;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;

/**
 * Represents configuraton of vacancy searching algorithms for HeadHunter
 */
class HeadHunter implements ConfigurationInterface
{
    /**
     * Repository for HeadHunter searching parameters
     *
     * @var HeadHunterParametersRepository
     */
    private $parametersRepository;

    /**
     * Parameters for vacancy searching on HeadHunter website
     *
     * @var HeadHunterParameters
     */
    private $_parameters;

    /**
     * HeadHunter constructor.
     *
     * @param HeadHunterParametersRepository $parametersRepository Repository for HeadHunter searching parameters
     */
    public function __construct(HeadHunterParametersRepository $parametersRepository)
    {
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
    public function getParameters()
    {
        $this->ensureParameters();

        return $this->_parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters($parameters): void
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
    }

    /**
     * Throws exception if configuration parameters have not been applied
     *
     * @return void
     */
    private function ensureParameters(): void
    {
        if (!$this->_parameters instanceof HeadHunterParameters) {
            throw new RoadmapConfigurationNotFoundException();
        }
    }
}
