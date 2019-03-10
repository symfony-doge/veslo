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

namespace Veslo\AnthillBundle\Fixture\Vacancy\Roadmap\Configuration;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\FileLoaderInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Veslo\AnthillBundle\Enum\Fixture\Group as AnthillGroup;
use Veslo\AppBundle\Enum\Fixture\Group as ApplicationGroup;

/**
 * Configuration parameters fixture for HeadHunter vacancy searching algorithms
 */
class HeadHunterParametersFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * Locates file by physical or logical '@' path
     *
     * @var FileLocatorInterface
     */
    private $fileLocator;

    /**
     * Loads a fixture files
     *
     * @var FileLoaderInterface
     */
    private $fixtureLoader;

    /**
     * Fixture path to load
     *
     * @var string
     */
    private $fixturePath;

    /**
     * HeadHunterParametersFixture constructor.
     *
     * @param FileLocatorInterface $fileLocator   Locates file by physical or logic '@' path
     * @param FileLoaderInterface  $fixtureLoader Loads a fixture files
     * @param string               $fixturePath   Fixture path to load
     */
    public function __construct(
        FileLocatorInterface $fileLocator,
        FileLoaderInterface $fixtureLoader,
        string $fixturePath
    ) {
        $this->fileLocator   = $fileLocator;
        $this->fixtureLoader = $fixtureLoader;
        $this->fixturePath   = $fixturePath;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $fixtureRealPath = $this->fileLocator->locate($this->fixturePath);
        $objectSet       = $this->fixtureLoader->loadFile($fixtureRealPath);
        $entities        = $objectSet->getObjects();

        foreach ($entities as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return [
            AnthillGroup::ROADMAP_CONFIGURATION_PARAMETERS,
            ApplicationGroup::PRODUCTION,
        ];
    }
}
