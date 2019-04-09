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

namespace Veslo\AppBundle\Integration\MinistryOfTruth;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyDoge\MinistryOfTruthClient\Dto\RequestDto;

/**
 * Grants a quick shortcut for configuring locale options to service that uses the Ministry of Truth API client
 *
 * @see RequestDto::$locale
 *
 * TODO: move to the motc symfony bridge layer as a service (?)
 */
trait LocaleOptionsTrait
{
    /**
     * Configures locale options of service for building requests to the Ministry of Truth API
     *
     * @param OptionsResolver $optionsResolver Validates options and merges them with default values
     *
     * @return void
     */
    protected function configureLocaleOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefault('locales', null);

        $optionsResolver->setDefault(
            'default_locale',
            function (Options $options, $previousValue) {
                if (!is_array($options['locales']) || !in_array($previousValue, $options['locales'], true)) {
                    throw new InvalidOptionsException(
                        'Value of the "default_locale" option is not present in locales array'
                    );
                }

                return $previousValue;
            }
        );

        $optionsResolver->setRequired(['default_locale', 'locales']);
    }
}
