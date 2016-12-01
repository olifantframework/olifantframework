<?php
namespace Olifant\Kernel;

use Closure;
use Relay\RelayBuilder;
use Zend\Diactoros\Response\SapiEmitter;
use Olifant\Controller\ControllerClosure;

class HttpApplication
{
    public function run(Application $app)
    {
        $path = $app->make('request')->getUri()->getPath();
        $route = $app->make('router')->go($path);

        $app->instance('route', $route);

        $callback = $route->getCallback();

        if ($callback instanceof Closure) {
            $callback = (new ControllerClosure)->bind($callback);
        }

        $queue = [
            new \Olifant\Middleware\Application($callback)
        ];

        $m = $route->getMiddleware();

        $relayBuilder = new RelayBuilder;
        $relay = $relayBuilder->newInstance($queue);
        $response = $relay(
            $app->make('request'),
            $app->make('response')
        );

        (new SapiEmitter)->emit($response);
    }
}