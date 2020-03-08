<?php

declare(strict_types=1);

namespace App\Service;

use App\Filter\EntryInputFilter;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class EntryServiceFactory
{
    public function __invoke(ContainerInterface $container) : EntryService
    {
        $filters = $container->get('InputFilterManager');

        return new EntryService(
            $container->get('doctrine.entity_manager.orm_default'),
            $filters->get(EntryInputFilter::class)
        );
    }
}
