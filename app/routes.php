<?php

declare(strict_types=1);

return function (\Slim\App $app) {
    // Lection CRUD
    $app->group('/lection', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Lection\Create::class);
        $router->get('s', \App\Application\Actions\Lection\ReadList::class);
        $router->group('/{id:[0-9]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Lection\Read::class);
            $router->put('', \App\Application\Actions\Lection\Update::class);
            $router->delete('', \App\Application\Actions\Lection\Delete::class);
        });
    });

    // Test CRUD
    $app->group('/test', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Test\Create::class);
        $router->get('s', \App\Application\Actions\Test\ReadList::class);
        $router->group('/{id:[0-9]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Test\Read::class);
            $router->put('', \App\Application\Actions\Test\Update::class);
            $router->delete('', \App\Application\Actions\Test\Delete::class);
        });
    });

    // Question CRUD
    $app->group('/question', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Question\Create::class);
        $router->get('s', \App\Application\Actions\Question\ReadList::class);
        $router->group('/{id:[0-9]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Question\Read::class);
            $router->put('', \App\Application\Actions\Question\Update::class);
            $router->delete('', \App\Application\Actions\Question\Delete::class);
        });
    });

    // Answer CRUD
    $app->group('/answer', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Answer\Create::class);
        $router->get('s', \App\Application\Actions\Answer\ReadList::class);
        $router->group('/{id:[0-9]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Answer\Read::class);
            $router->put('', \App\Application\Actions\Answer\Update::class);
            $router->delete('', \App\Application\Actions\Answer\Delete::class);
        });
    });

    // Score CRUD
    $app->group('/score', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
        $router->post('', \App\Application\Actions\Score\Create::class);
        $router->get('s', \App\Application\Actions\Score\ReadList::class);
        $router->group('/{id:[0-9]+}', function (\Slim\Interfaces\RouteCollectorProxyInterface $router) {
            $router->get('', \App\Application\Actions\Score\Read::class);
            $router->put('', \App\Application\Actions\Score\Update::class);
            $router->delete('', \App\Application\Actions\Score\Delete::class);
        });
    });
};
