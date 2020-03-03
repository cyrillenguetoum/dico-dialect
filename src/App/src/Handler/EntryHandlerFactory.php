<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use App\Repository\EntryRepository;

use function get_class;

class EntryHandlerFactory
{
    public function __invoke(ContainerInterface $container) : EntryHandler
    {
        return new EntryHandler(
            $container->get(EntryRepository::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class)
        );
    }
}
