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
