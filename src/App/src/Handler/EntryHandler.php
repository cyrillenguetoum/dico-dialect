<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use App\Service\EntryService;
use App\RestDispatchTrait;

class EntryHandler implements RequestHandlerInterface
{
    /** @var EntryRepository */
    private $repository;

    use RestDispatchTrait;

    public function __construct(
        EntryService $service,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    ) {
        $this->repository = $service;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function get(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', false);
        return false == $id
            ? $this->getAllEntries($request)
            : $this->getEntry((int) $id, $request);
    }

    public function getEntry(int $id, ServerRequestInterface $request): ResponseInterface
    {
        return $this->createResponse(
            $request,
            $this->repository->find($id)
        );
    }

    public function getAllEntries(ServerRequestInterface $request): ResponseInterface
    {
        $page = $request->getQueryParams()['page'] ?? 1;
        $entries = $this->repository->findAll();
        $entries->setItemCountPerPage(5);
        $entries->setCurrentPageNumber($page);
        return $this->createResponse($request, $entries);
    }
}
