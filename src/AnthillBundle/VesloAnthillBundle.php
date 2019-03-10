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

namespace Veslo\AnthillBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Veslo\AnthillBundle\DependencyInjection\Compiler\VacancyRoadmapPass;
use Veslo\AnthillBundle\DependencyInjection\Compiler\VacancyScannerPass;

/**
 * Anthill bundle.
 */
class VesloAnthillBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new VacancyRoadmapPass());
        $container->addCompilerPass(new VacancyScannerPass());
    }
}
