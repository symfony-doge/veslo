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

namespace Veslo\AnthillBundle\Vacancy\Decision;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Event\Vacancy\Decision\AppliedEvent;
use Veslo\AnthillBundle\Vacancy\DecisionInterface;
use Veslo\AnthillBundle\Vacancy\Resolver as VacancyResolver;

/**
 * Will be applied whenever a specified vacancy data is not already collected and managed before
 */
class NotADuplicate implements DecisionInterface
{
    /**
     * Describes conditions for ensuring that decision can be applied
     *
     * @const string[]
     */
    public const CONDITIONS = ['Vacancy is not a duplicate of an existing one within roadmap'];

    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Extracts a vacancy instance from the specified context
     *
     * @var VacancyResolver
     */
    private $vacancyResolver;

    /**
     * Dispatches a decision event to listeners
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * NotADuplicate constructor.
     *
     * @param LoggerInterface          $logger          Logger as it is
     * @param VacancyResolver          $vacancyResolver Extracts a vacancy instance from the specified context
     * @param EventDispatcherInterface $eventDispatcher Dispatches a decision event to listeners
     */
    public function __construct(
        LoggerInterface $logger,
        VacancyResolver $vacancyResolver,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->logger          = $logger;
        $this->vacancyResolver = $vacancyResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     *
     * @var ParsedDto $context Context of vacancy data at 'parsed' place in workflow
     */
    public function isApplied(object $context): bool
    {
        if (!$context instanceof ParsedDto) {
            throw new InvalidArgumentException('Collectable decision should be applied to ParsedDto.');
        }

        $location = $context->getLocation();

        if (empty($location)) {
            $this->logger->warning('Vacancy location is not found in parsed data context.');

            return false;
        }

        $roadmap = $location->getRoadmap();

        if (empty($roadmap)) {
            $this->logger->warning('Vacancy roadmap is not found in parsed data context.');

            return false;
        }

        $entity = $this->vacancyResolver->resolveByParsedDto($context);

        $isDuplicateFound = $entity instanceof Vacancy;
        $isApplied        = !$isDuplicateFound;

        $appliedEvent = new AppliedEvent($this, $context, $isApplied);
        $this->eventDispatcher->dispatch(AppliedEvent::NAME, $appliedEvent);

        return $isApplied;
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions(): iterable
    {
        return self::CONDITIONS;
    }
}
