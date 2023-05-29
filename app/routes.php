<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
//    $app->options('/{routes:.*}', function (Request $request, Response $response) {
//        // CORS Pre-Flight OPTIONS Request Handler
//        return $response;
//    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(  file_get_contents(public_path().'/files/fd1b6e1a5cedbeb57bf160c27ac2645f.pdf'));
//        $response->getBody()->write( $_SERVER['HTTP_HOST']);
        return $response;
    });

    $app->group('/migrate-orders-documents', function (Group $group) {
        $group->post('', \App\Application\Actions\OrderDocumentMigrator\MigrateOrdersDocumentsAction::class);
    });

    $app->group('/migrate-order-documents', function (Group $group) {
        $group->post('', \App\Application\Actions\OrderDocumentMigrator\MigrateOrderDocumentsAction::class);
    });

    $app->group('/import-products', function (Group $group) {
        $group->post('', \App\Application\Actions\ProductsImport\ImportProductsAction::class);
    });
};
