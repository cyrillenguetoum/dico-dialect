<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use App\Repository\EntryRepository;

class IndexHandlerFactory
{
    public function __invoke(ContainerInterface $container) : IndexHandler
    {
        return new IndexHandler($container->get(EntryRepository::class));
    }
}
