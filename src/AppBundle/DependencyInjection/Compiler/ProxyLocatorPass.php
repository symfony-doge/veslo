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

namespace Veslo\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Veslo\AppBundle\Http\Proxy\LocatorChain;
use Veslo\AppBundle\Http\Proxy\LocatorInterface;

/**
 * Aggregates available locators into locator chain for proxy list resolving
 *
 * @see LocatorChain
 */
class ProxyLocatorPass implements CompilerPassInterface
{
    /**
     * Service id for aggregation
     *
     * @const string
     */
    private const SERVICE_ID_AGGREGATOR = 'veslo.app.http.proxy.locator_chain';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $proxyLocatorServiceIds = $container->findTaggedServiceIds(LocatorInterface::TAG);
        $locatorChainDefinition = $container->getDefinition(self::SERVICE_ID_AGGREGATOR);

        foreach ($proxyLocatorServiceIds as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $priority    = $attributes['priority'] ?? 0;
                $isImportant = !empty($attributes['isImportant']);

                $proxyLocatorReference = new Reference($serviceId);
                $locatorChainDefinition->addMethodCall('addLocator', [$proxyLocatorReference, $priority, $isImportant]);
            }
        }
    }
}
