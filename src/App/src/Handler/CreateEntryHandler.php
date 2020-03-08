<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Mezzio\Helper\UrlHelper;
use App\Service\EntryService;
use App\RestDispatchTrait;

class CreateEntryHandler implements RequestHandlerInterface
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

    public function post(ServerRequestInterface $request) : ResponseInterface
    {
        $result = $this->service->addEntry($request->getParsedBody());
        $response = $this->createResponse($request, $result);

        return $response->withStatus(201)->withHeader(
            'location',
            $this->helper->generate('api.entries', ['id' => $result->getId()])
        );
    }
}
