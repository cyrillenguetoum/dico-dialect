<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', App\Handler\IndexHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');

    /**** This routes presents to you interaction with Dictionnary entries ******/
    $app->get('/api/entries[/{id}]', App\Handler\EntryHandler::class, 'api.entries');
    $app->post('/api/entries', [
        BodyParamsMiddleware::class,
        App\Handler\CreateEntryHandler::class
    ]);
    $app->route('/api/entries/{id}', [
        BodyParamsMiddleware::class,
        App\Handler\ModifyEntryHandler::class,
    ], ['PATCH', 'DELETE'], 'api.entry');

    /** This route initializes or updates Lucene search Indexes and perform word searching **/
    $app->get('/api/search', App\Handler\SearchHandler::class, 'api.search');

    /********************************  API docs  ************************************/
    $app->get('/api/doc/invalid-parameter', App\Doc\InvalidParameterHandler::class);
    $app->get('/api/doc/method-not-allowed-error', App\Doc\MethodNotAllowedHandler::class);
    $app->get('/api/doc/resource-not-found', App\Doc\ResourceNotFoundHandler::class);
    $app->get('/api/doc/parameter-out-of-range', App\Doc\OutOfBoundsHandler::class);
    $app->get('/api/doc/runtime-error', App\Doc\RuntimeErrorHandler::class);
};
