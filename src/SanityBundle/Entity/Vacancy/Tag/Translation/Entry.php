<?php

declare(strict_types=1);

namespace Veslo\SanityBundle\Entity\Vacancy\Tag\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Veslo\SanityBundle\Entity\Vacancy\Tag;

/**
 * Translation entry for a field of sanity tag entity
 *
 * Note: personal translation is chosen because this data is received from external source,
 * so each time we perform sync, old translation entries have to be deleted (cascade)
 *
 * @ORM\Table(
 *     name="sanity_vacancy_tag_translation",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="sanity_vacancy_tag_translation_locale_object_id_field_uq",
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
     * @var Tag
     *
     * @ORM\ManyToOne(targetEntity="Veslo\SanityBundle\Entity\Vacancy\Tag", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * {@inheritdoc}
     *
     * @var Tag $object Sanity tag
     */
    public function setObject($object)
    {
        parent::setObject($object);

        $object->addTranslation($this);

        return $this;
    }
}
