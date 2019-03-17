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

namespace Veslo\AppBundle\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\FileLoaderInterface;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Fixture that loads entities from file
 */
class FileFixture extends Fixture
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
     * FileFixture constructor.
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
}
