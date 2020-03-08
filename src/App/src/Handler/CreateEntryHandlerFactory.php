<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Mezzio\Helper\UrlHelper;
use App\Service\EntryService;

class CreateEntryHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CreateEntryHandler
    {
        return new CreateEntryHandler(
            $container->get(EntryService::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class),
            $container->get(UrlHelper::class)
        );
    }
}
