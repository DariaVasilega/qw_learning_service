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
};
