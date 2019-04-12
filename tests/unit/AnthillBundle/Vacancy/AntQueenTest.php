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

namespace Veslo\Tests\Unit\AnthillBundle\Vacancy;

use Codeception\AssertThrows;
use Codeception\Specify;
use Codeception\Test\Unit;
use Veslo\AnthillBundle\Exception\Vacancy\Roadmap\ConfigurationNotSupportedException;
use Veslo\AnthillBundle\Exception\Vacancy\RoadmapNotFoundException;
use Veslo\AnthillBundle\Vacancy\AntQueen;
use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;
use Veslo\AnthillBundle\Vacancy\RoadmapInterface;

class AntQueenTest extends Unit
{
    use Specify;
    use AssertThrows;

    /**
     * @var AntQueen
     *
     * @specify
     */
    private $antQueen;

    protected function _before()
    {
        $this->antQueen = new AntQueen();
    }

    public function testAddRoadmap()
    {
        $this->describe('antQueen', function() {
            $this->it('should aggregate roadmaps', function () {
                verify_not($this->antQueen->getRoadmaps());

                /** @var RoadmapInterface $roadmap */
                $roadmap = $this->makeEmpty(RoadmapInterface::class);
                $this->antQueen->addRoadmap('testRoadmapName', $roadmap);

                verify_that($this->antQueen->getRoadmaps());
                verify($this->antQueen->getRoadmaps())->containsOnlyInstancesOf(RoadmapInterface::class);
            });
        });
    }

    public function testGetRoadmaps()
    {
        $this->describe('antQueen', function() {
            $this->it('should return roadmap list', function () {
                verify_not($this->antQueen->getRoadmaps());

                /** @var RoadmapInterface $simpleRoadmap */
                $simpleRoadmap = $this->makeEmpty(RoadmapInterface::class);
                $this->antQueen->addRoadmap('simpleRoadmap', $simpleRoadmap);

                /** @var ConfigurableRoadmapInterface $configurableRoadmap */
                $configurableRoadmap = $this->makeConfigurableRoadmapStub();
                $this->antQueen->addRoadmap('configurableRoadmap', $configurableRoadmap);

                verify($this->antQueen->getRoadmaps())->count(2);
                verify($this->antQueen->getRoadmaps())->containsOnlyInstancesOf(RoadmapInterface::class);
            });
        });
    }

    public function testRequireRoadmap()
    {
        $this->describe('antQueen', function() {
            $this->it('should return roadmap with guarantee', function () {
                $this->assertThrows(RoadmapNotFoundException::class, function() {
                    $this->antQueen->requireRoadmap('requiredRoadmapName');
                });

                /** @var RoadmapInterface $roadmap */
                $roadmap = $this->makeEmpty(RoadmapInterface::class);
                $this->antQueen->addRoadmap('requiredRoadmapName', $roadmap);

                try {
                    verify($this->antQueen->requireRoadmap('requiredRoadmapName'))->isInstanceOf(RoadmapInterface::class);
                } catch (RoadmapNotFoundException $e) {
                    $this->fail('Roadmap is not found after addition.');
                }
            });
        });
    }

    public function testBuildRoadmap()
    {
        $this->describe('antQueen', function() {
            $this->it('should build a simple roadmap without configuration', function () {
                /** @var RoadmapInterface $simpleRoadmap */
                $simpleRoadmap = $this->makeEmpty(RoadmapInterface::class);
                $this->antQueen->addRoadmap('simpleRoadmap', $simpleRoadmap);

                try {
                    verify($this->antQueen->buildRoadmap('simpleRoadmap'))->isInstanceOf(ConveyorAwareRoadmap::class);
                } catch (RoadmapNotFoundException $e) {
                    $this->fail('Roadmap is not found after addition.');
                }
            });

            $this->it('should not build a simple roadmap with any configuration', function () {
                /** @var RoadmapInterface $simpleRoadmap */
                $simpleRoadmap = $this->makeEmpty(RoadmapInterface::class);
                $this->antQueen->addRoadmap('simpleRoadmap', $simpleRoadmap);

                $this->assertThrows(ConfigurationNotSupportedException::class, function() {
                    try {
                        $this->antQueen->buildRoadmap('simpleRoadmap', 'notExpectedKeyForConfiguration');
                    } catch (RoadmapNotFoundException $e) {
                        $this->fail('Roadmap is not found after addition.');
                    }
                });
            });

            $this->it('should build a configurable roadmap by configuration key', function () {
                /** @var ConfigurableRoadmapInterface $configurableRoadmap */
                $configurableRoadmap = $this->makeConfigurableRoadmapStub();
                $this->antQueen->addRoadmap('configurableRoadmap', $configurableRoadmap);

                try {
                    verify(
                        $this->antQueen->buildRoadmap('configurableRoadmap', 'expectedConfigurationKey')
                    )->isInstanceOf(ConveyorAwareRoadmap::class);
                } catch (RoadmapNotFoundException $e) {
                    $this->fail('Roadmap is not found after addition.');
                }
            });
        });
    }

    private function makeConfigurableRoadmapStub()
    {
        return $this->makeEmpty(
            ConfigurableRoadmapInterface::class,
            [
                'getConfiguration' => function () {
                    return $this->makeEmpty(ConfigurationInterface::class);
                }
            ]
        );
    }
}
