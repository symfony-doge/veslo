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

namespace Veslo\SanityBundle\Vacancy\Tag\Group\Provider;

use SymfonyDoge\MinistryOfTruthClient\ClientInterface;
use Veslo\SanityBundle\Vacancy\Tag\Group\ProviderInterface;

/**
 * Uses the Ministry of Truth API to provide sanity tag groups for vacancy analysers
 */
class MinistryOfTruth implements ProviderInterface
{
    /**
     * The Ministry of Truth API client
     *
     * @var ClientInterface
     */
    private $motClient;

    /**
     * {@inheritdoc}
     */
    public function getTagGroups(): array
    {
        // TODO
    }
}
