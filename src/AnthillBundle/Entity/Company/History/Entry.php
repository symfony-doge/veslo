<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Entity\Company\History;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

/**
 * Company history entry
 *
 * @ORM\Table(
 *     name="anthill_company_history",
 *     options={"row_format": "DYNAMIC"},
 *     indexes={
 *         @ORM\Index(name="anthill_company_history_object_class_ix", columns={"object_class"}),
 *         @ORM\Index(name="anthill_company_history_logged_at_ix", columns={"logged_at"}),
 *         @ORM\Index(name="anthill_company_history_username_ix", columns={"username"}),
 *         @ORM\Index(
 *             name="anthill_company_history_object_id_object_class_version_ix",
 *             columns={"object_id", "object_class", "version"}
 *         )
 *     }
 * )
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository", readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="history")
 */
class Entry extends AbstractLogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
}
