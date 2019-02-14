<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Configuration;

/**
 * Represents searching criteria parameters for roadmap configuration
 */
interface ParametersInterface
{
    /**
     * Returns configuration key to which parameters record belongs to
     *
     * @return string
     */
    public function getConfigurationKey(): string;

    /**
     * Returns vacancy provider's URI
     *
     * @return string
     */
    public function getProviderUri(): string;
}
