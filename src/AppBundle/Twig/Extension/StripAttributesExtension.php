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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Veslo\AppBundle\Html\Tag\AttributeRemover;

/**
 * Removes any attributes from tags in html string
 */
class StripAttributesExtension extends AbstractExtension
{
    /**
     * Removes attributes from tags in html string
     *
     * @var AttributeRemover
     */
    private $attributeRemover;

    /**
     * StripAttributesExtension constructor.
     *
     * @param AttributeRemover $attributeRemover Removes attributes from tags in html string
     */
    public function __construct(AttributeRemover $attributeRemover)
    {
        $this->attributeRemover = $attributeRemover;
    }

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

        return $this->attributeRemover->removeAttributes($html);
    }
}
