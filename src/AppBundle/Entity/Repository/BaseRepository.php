<?php

namespace Veslo\AppBundle\Entity\Repository;

use Veslo\AppBundle\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * Provides additional contract-based methods for standard repository api.
 */
abstract class BaseRepository extends EntityRepository
{
    /**
     * Returns an entity by its identifier or throws exception
     *
     * @param mixed    $id          The identifier.
     * @param int|null $lockMode    One of the \Doctrine\DBAL\LockMode::* constants
     *                              or NULL if no specific lock mode should be used
     *                              during the search.
     * @param int|null $lockVersion The lock version.
     *
     * @return object The entity instance.
     *
     * @throws EntityNotFoundException
     */
    public function require($id, $lockMode = null, $lockVersion = null)
    {
        $entity = $this->find($id, $lockMode, $lockVersion);

        $classMetadata = $this->getClassMetadata();

        if (!$entity instanceof $classMetadata->name) {
            throw EntityNotFoundException::withClassAndId($classMetadata->name, $id);
        }

        return $entity;
    }
}
