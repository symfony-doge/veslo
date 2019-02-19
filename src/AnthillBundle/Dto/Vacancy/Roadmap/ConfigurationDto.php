<?php

declare(strict_types=1);

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
     * @param string $key Configuration key
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
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }
}
