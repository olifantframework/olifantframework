<?php
namespace Olifant\Service;

use Beacon\Router;

class RouterServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $request = $app->make('request');

        $router = new Router([
            'host'      => $request->getUri()->getHost(),
            'method'    => $request->getMethod(),
            'isSecured' => $request->isSecure()
        ]);

        $app->instance('router', $router);
    }
}