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

namespace Veslo\AppBundle\Twig\Extension;

use DOMDocument;
use DOMXPath;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Removes any attributes from tags in html string
 */
class StripAttributesExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('stripattributes', [$this, 'removeAttributesFromTags']),
        ];
    }

    /**
     * Removes any attributes from tags in html string
     *
     * @param string $html Html string
     *
     * @return string|null
     */
    public function removeAttributesFromTags(?string $html): ?string
    {
        if (empty($html)) {
            return $html;
        }

        $dom = new DOMDocument();

        // https://stackoverflow.com/a/8218649/3121455
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//@*');

        foreach ($nodes as $node) {
            $node->parentNode->removeAttribute($node->nodeName);
        }

        $bodyNodeList = $dom->getElementsByTagName('body');
        $bodyNode     = $bodyNodeList->item(0);
        $resultHtml   = '';

        foreach ($bodyNode->childNodes as $childNode) {
            $resultHtml .= $dom->saveHTML($childNode);
        }

        return $resultHtml;
    }
}
