<?php
namespace Olifant\Service;

use Beacon\Router;
use Olifant\Kernel\Utils;
use Olifant\Kernel\KernelException;

class RouterServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        if (Utils::isCLI()) {
            return $app->bind('router', function() {
                throw new KernelException(
                    RouterServiceProvider::class . ' disabled in CLI mode'
                );
            });
        }

        $request = $app->make('request');

        $router = new Router([
            'host'      => $request->getUri()->getHost(),
            'method'    => $request->getMethod(),
            'isSecured' => $request->isSecure()
        ]);

        $app->instance('router', $router);
    }
}