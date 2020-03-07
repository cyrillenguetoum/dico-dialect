<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\ArrayAdapter;
use App\Entity\EntryCollection;

abstract class GenericRepository
{
    /**
     * Doctrine Entity Manager
     * @var EntityManager
     */
    protected $em;

    /**
     * Entity to query
     */
    protected $entity;

    final function __construct(EntityManager $em, $entity) {
        $this->em = $em;
        $this->entity = $entity;
    }

    /**
     * Gets all the entities objects saved in database
     * Either in an array or encapsulated in a paginator
     */
    public function findAll($raw = false)
    {
        $entities = $this->em->getRepository($this->entity)->findAll();
        if ($raw === true) {
            return $entities;
        }
        $adapter = new ArrayAdapter($entities);
        $paginator = new EntryCollection($adapter);
        return $paginator;
    }

    /// Retrieve an entity based on his id
    public function find(int $id)
    {
        return $this->em->find($this->entity, $id);
    }
}
