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

namespace Veslo\SanityBundle\Entity\Vacancy\Tag\Group\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;

/**
 * Translation entry for a field of sanity group entity
 *
 * Note: personal translation is chosen because this data is received from external source,
 * so each time we perform sync, old translation entries have to be deleted (cascade)
 *
 * @ORM\Table(
 *     name="sanity_vacancy_tag_group_translation",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="sanity_vacancy_tag_group_translation_locale_object_id_field_uq",
 *             columns={"locale", "object_id", "field"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository", readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="index")
 */
class Entry extends AbstractPersonalTranslation
{
    /**
     * Translatable entity
     *
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Veslo\SanityBundle\Entity\Vacancy\Tag\Group", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * {@inheritdoc}
     *
     * @var Group $object Group for sanity tags
     */
    public function setObject($object)
    {
        parent::setObject($object);

        $object->addTranslation($this);

        return $this;
    }
}
