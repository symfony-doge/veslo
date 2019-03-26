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

namespace Veslo\AppBundle\Html\Tag;

use DOMDocument;
use DOMXPath;

/**
 * Removes attributes from tags in html string
 */
class AttributeRemover
{
    /**
     * Returns HTML string with removed tag attributes
     *
     * @param string $html HTML string
     *
     * @return string
     */
    public function removeAttributes(string $html): string
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
