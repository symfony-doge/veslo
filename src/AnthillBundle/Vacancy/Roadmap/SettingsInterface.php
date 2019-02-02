<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

/**
 * Represents configuration settings and website context for roadmap,
 * ex. website url, query criteria or vacancy identifier for next lookup
 */
interface SettingsInterface
{
    /**
     * Returns vacancy site URL for queries
     * For example, "https://api.hh.ru"
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Returns vacancy identifier for next lookup
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Returns query text for next lookup
     *
     * @return string
     */
    public function getQuery(): string;

    /**
     * Returns vacancy location for next lookup
     *
     * @return string
     */
    public function getLocation(): string;
}
