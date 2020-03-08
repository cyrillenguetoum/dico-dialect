<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Mezzio\Helper\UrlHelper;
use App\Service\EntryService;
use App\RestDispatchTrait;

class ModifyEntryHandler implements RequestHandlerInterface
{
    /** @var EntryService **/
    private $service;

    /** @var UrlHelper **/
    private $helper;

    use RestDispatchTrait;

    public function __construct(
        EntryService $service,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory,
        UrlHelper $helper
    ) {
        $this->service = $service;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
        $this->helper = $helper;
    }

    public function patch(ServerRequestInterface $request) : ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $entry = $this->service->updateEntry($id, $request->getParsedBody());
        return $this->createResponse($request, $entry);
    }

    public function delete(ServerRequestInterface $request) : ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $this->service->deleteEntry($id);
        return new EmptyResponse(204);
    }
}
