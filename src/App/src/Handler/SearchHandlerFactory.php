<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use App\Service\EntryService;

class SearchHandlerFactory
{
    public function __invoke(ContainerInterface $container) : SearchHandler
    {
        return new SearchHandler($container->get(EntryService::class));
    }
}
