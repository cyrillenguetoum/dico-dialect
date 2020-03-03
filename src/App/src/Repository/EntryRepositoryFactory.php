<?php
declare(strict_types=1);

namespace App\Repository;

use Psr\Container\ContainerInterface;
use App\Entity\Entry;

class EntryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): EntryRepository
    {
        return new EntryRepository(
            $container->get('doctrine.entity_manager.orm_default'),
            Entry::class
        );
    }

}
