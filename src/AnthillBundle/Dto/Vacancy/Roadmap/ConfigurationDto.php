<?php

namespace Veslo\AnthillBundle\Dto\Vacancy\Roadmap;

/**
 * Context of configuration for searching algorithm used by roadmap
 */
class ConfigurationDto
{
    /**
     * Configuration key
     *
     * @var string
     */
    private $key;

    /**
     * Sets configuration key
     *
     * @param string $key
     *
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Returns configuration key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}