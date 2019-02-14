<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Digger;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Veslo\AnthillBundle\Vacancy\DiggerInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;

/**
 * Cranky dung beetle temporarily refuses to dig if it had too much unsuccessful attempts in a row
 * So stressful, be nice with him.
 *
 * @see DungBeetle
 */
class CrankyDungBeetle implements DiggerInterface
{
    /**
     * Cache for handling pause on too much unsuccessful search attempts and other values
     *
     * @const string
     */
    private const CACHE_NAMESPACE = 'veslo.anthill.vacancy.cranky_dung_beetle';

    /**
     * Cache instance for managing pause state of roadmaps
     *
     * @var AdapterInterface
     */
    private $cache;

    /**
     * Count of unsuccessful vacancy search attempts after which dung beetle will temporarily stops digging
     *
     * This prevents external website DDoSing and possible rate limiting
     *
     * @var int
     */
    private $attemptsUntilPause;

    /**
     * Digging pause duration (in seconds)
     *
     * Dung beetle will refuse dig command if it doesn't get
     * any vacancies by roadmap {$attemptsUntilPause} times in a row
     *
     * @var int
     */
    private $pauseDuration;

    /**
     * Provides base implementation of digging logic
     *
     * @var DungBeetle
     */
    private $_dungBeetle;

    /**
     * CrankyDungBeetle constructor.
     *
     * @param DungBeetle       $dungBeetle         Provides base implementation of digging logic
     * @param AdapterInterface $cache              Cache instance for managing pause state of roadmaps
     * @param int              $attemptsUntilPause Count of unsuccessful vacancy search attempts for pausing
     * @param int              $pauseDuration      Digging pause duration (in seconds)
     */
    public function __construct(
        DungBeetle $dungBeetle,
        AdapterInterface $cache,
        int $attemptsUntilPause,
        int $pauseDuration
    ) {
        $this->_dungBeetle        = $dungBeetle;
        $this->cache              = $cache;
        $this->attemptsUntilPause = $attemptsUntilPause;
        $this->pauseDuration      = $pauseDuration;
    }

    /**
     * Digs dung (vacancies) from internet by specified roadmap and attempts count
     * Temporarily stops digging if too much unsuccessful attempts occurred
     *
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function dig(ConveyorAwareRoadmap $roadmap, int $iterations = 1): int
    {
        if ($this->isPaused($roadmap)) {
            $logger      = $this->_dungBeetle->getLogger();
            $roadmapName = $roadmap->getName();

            $logger->debug('Roadmap is on pause.', ['roadmap' => $roadmapName]);

            return 0;
        }

        $successfulIterations = $this->_dungBeetle->dig($roadmap, $iterations);

        if (empty($successfulIterations)) {
            $this->pause($roadmap);
        }

        return $successfulIterations;
    }

    /**
     * Returns positive if roadmap is paused
     *
     * @param ConveyorAwareRoadmap $roadmap Provides URL of vacancies
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    private function isPaused(ConveyorAwareRoadmap $roadmap): bool
    {
        $pause = $this->getPause($roadmap->getName());

        if (!$pause->isHit()) {
            return false;
        }

        $unsuccessfulAttempts = (int) $pause->get();

        return $unsuccessfulAttempts >= $this->attemptsUntilPause;
    }

    /**
     * Pausing roadmap that makes it unusable by dung beetle for {$pauseDuration} seconds
     *
     * @param ConveyorAwareRoadmap $roadmap Provides URL of vacancies
     *
     * @throws InvalidArgumentException
     */
    private function pause(ConveyorAwareRoadmap $roadmap): void
    {
        $pause = $this->getPause($roadmap->getName());
        $pause->expiresAfter($this->pauseDuration);

        $unsuccessfulAttempts = $pause->isHit() ? (int) $pause->get() : 0;

        if ($unsuccessfulAttempts >= $this->attemptsUntilPause) {
            return;
        }

        $pause->set($unsuccessfulAttempts + 1);
        $this->cache->save($pause);
    }

    /**
     * Returns cache item that represents pause state with unsuccessful attempts counter
     *
     * @param string $roadmapName Name of vacancy URL provider
     *
     * @return CacheItem
     *
     * @throws InvalidArgumentException
     */
    private function getPause(string $roadmapName): CacheItem
    {
        $cacheKey = implode('.', [static::CACHE_NAMESPACE, 'dig', $roadmapName]);

        return $this->cache->getItem($cacheKey);
    }
}
