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

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Veslo\AppBundle\Cache\Cacher;
use Veslo\AppBundle\Exception\Http\Proxy\Locator\BadProxyListUriException;
use Veslo\AppBundle\Http\Proxy\LocatorInterface;

/**
 * Uses URI string to fetch a proxy list from external source
 *
 * Note: you should manage to use an algorithm that doesn't depend on this locator itself
 * to avoid a cycling references while resolving a proxy list.
 */
class UriLocator implements LocatorInterface
{
    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Decodes a string into PHP data
     *
     * @var DecoderInterface
     */
    private $contentsDecoder;

    /**
     * Saves a proxy list in the cache and invalidates it by demand
     *
     * @var Cacher
     */
    private $proxyCacher;

    /**
     * Options for URI proxy locator
     *
     * Example:
     * ```
     * [
     *     'uri' => 'https://proxy-provider.ltd/my_proxy_list',
     *     'format' => 'json',
     *     'decoder_context' => []
     * ]
     * ```
     *
     * @var array
     */
    private $options;

    /**
     * UriLocator constructor.
     *
     * @param LoggerInterface  $logger          Logger as it is
     * @param DecoderInterface $contentsDecoder Decodes a string into PHP data
     * @param array            $options         Options for URI proxy locator
     */
    public function __construct(LoggerInterface $logger, DecoderInterface $contentsDecoder, array $options)
    {
        $this->logger          = $logger;
        $this->contentsDecoder = $contentsDecoder;
        $this->proxyCacher     = null;

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadProxyListUriException
     */
    public function locate(): iterable
    {
        $proxyListUri = $this->options['uri'];
        $uriContents  = false;

        try {
            $uriContents = file_get_contents($proxyListUri);
        } catch (Exception $e) {
            $message = $e->getMessage();

            $this->logger->error(
                'An error has been occurred while getting contents by URI.',
                [
                    'message' => $message,
                    'uri'     => $proxyListUri,
                ]
            );
        }

        if (false === $uriContents) {
            throw BadProxyListUriException::withProxyListUri($proxyListUri);
        }

        $uriContentsFormat = $this->options['format'];
        $decoderContext    = $this->options['decoder_context'];
        $proxyList         = $this->contentsDecoder->decode($uriContents, $uriContentsFormat, $decoderContext);

        // TODO: would be safer to use a converter here which will explicitly convert decoded data to an array.
        // Such thing will depend on data provider and their APIs, for now this logic is pretty enough.

        if ($this->proxyCacher instanceof Cacher) {
            $this->proxyCacher->save($proxyList);
        }

        return $proxyList;
    }

    /**
     * Sets a proxy list cacher
     *
     * @param Cacher $proxyCacher Saves a proxy list in the cache and invalidates it by demand
     *
     * @return void
     */
    public function setCacher(Cacher $proxyCacher): void
    {
        $this->proxyCacher = $proxyCacher;
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
        $optionsResolver->setDefaults(
            [
                'uri'             => null,
                'format'          => null,
                'decoder_context' => [],
            ]
        );

        $optionsResolver->setAllowedTypes('uri', ['string']);
        $optionsResolver->setAllowedTypes('decoder_context', ['array']);
        $optionsResolver->setRequired(['uri', 'format']);
    }
}
