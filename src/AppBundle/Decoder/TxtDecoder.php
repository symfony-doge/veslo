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

namespace Veslo\AppBundle\Decoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Converts a plain text string into array of lines by specified delimiter
 */
class TxtDecoder implements DecoderInterface
{
    /**
     * Format supported by decoder
     *
     * @const string
     */
    private const FORMAT = 'txt';

    /**
     * Line delimiter
     *
     * @var string
     */
    private $lineDelimiter;

    /**
     * TxtDecoder constructor.
     *
     * @param string $lineDelimiter Line delimiter
     */
    public function __construct(string $lineDelimiter = "\n")
    {
        $this->lineDelimiter = $lineDelimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = [])
    {
        if (empty($data)) {
            return [];
        }

        $array = explode($this->lineDelimiter, $data);

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return self::FORMAT === $format;
    }
}
