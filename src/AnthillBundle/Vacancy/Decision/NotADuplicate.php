<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Decision;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Entity\Repository\VacancyRepository;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AnthillBundle\Vacancy\DecisionInterface;

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
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * NotADuplicate constructor.
     *
     * @param LoggerInterface   $logger            Logger as it is
     * @param VacancyRepository $vacancyRepository Vacancy repository
     */
    public function __construct(LoggerInterface $logger, VacancyRepository $vacancyRepository)
    {
        $this->logger            = $logger;
        $this->vacancyRepository = $vacancyRepository;
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

        $roadmapName = $roadmap->getName();

        $vacancy            = $context->getVacancy();
        $externalIdentifier = $vacancy->getExternalIdentifier();

        $entity = $this->vacancyRepository->findByRoadmapNameAndExternalIdentifier($roadmapName, $externalIdentifier);

        $isDuplicateFound = $entity instanceof Vacancy;

        return !$isDuplicateFound;
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions(): array
    {
        return self::CONDITIONS;
    }
}
