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

namespace Veslo\AppBundle\Http\Proxy\Locator;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Veslo\AppBundle\Exception\Http\Proxy\Locator\BadProxyListUriException;
use Veslo\AppBundle\Http\Proxy\LocatorInterface;

/**
 * Uses URI string to fetch a proxy list from external source
 *
 * Note: you should manage to use a client that doesn't depend on this locator
 * to avoid a cycling references while resolving a proxy list.
 */
class UriLocator implements LocatorInterface
{
    /**
     * Decodes a string into PHP data
     *
     * @var DecoderInterface
     */
    private $contentsDecoder;

    /**
     * Options for URI proxy locator
     *
     * Example:
     * ```
     * [
     *     'uri' => 'https://proxy-provider.ltd/my_proxy_list',
     *     'format' => 'json'
     * ]
     * ```
     *
     * @var array
     */
    private $options;

    /**
     * UriLocator constructor.
     *
     * @param DecoderInterface $contentsDecoder Decodes a string into PHP data
     * @param array            $options         Options for URI proxy locator
     */
    public function __construct(DecoderInterface $contentsDecoder, array $options)
    {
        $this->contentsDecoder = $contentsDecoder;

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadProxyListUriException
     */
    public function locate(): array
    {
        $proxyListUri = $this->options['uri'];
        $uriContents  = file_get_contents($proxyListUri);

        if (false === $uriContents) {
            // TODO: move @throws to a interface signature, make this extended.
            throw BadProxyListUriException::withProxyListUri($proxyListUri);
        }

        $proxyList = $this->contentsDecoder->decode($uriContents, $this->options['format']);

        return $proxyList;
    }

    /**
     * Configures URI proxy locator options
     *
     * @param OptionsResolver $optionsResolver Validates options and merges them with default values
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(['uri' => null, 'format' => null]);

        $optionsResolver->setAllowedTypes('uri', ['string']);
        $optionsResolver->setRequired(['uri', 'format']);
    }
}
