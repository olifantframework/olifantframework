<?php
namespace Olifant\Middleware;

use Closure;
use Olifant\App;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Application
{
    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    private function fresh(Response $response)
    {
        return App::decorator('response', function() use ($response) {
            return $response;
        });
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $response = $next($request, $response);
        $this->fresh($response);

        $app = App::make('app');
        $response = $app->make($this->callback);

        if (! $response instanceof \Psr\Http\Message\ResponseInterface) {
            if ($response instanceof \Olifant\Response) {
                $response = $app->make('response');
            } else if (is_array($response) or is_object($response)) {
                $response = $app
                    ->make('response')
                    ->toJsonResponse()
                    ->withData($response);
            } else {
                $echo = (string)$response;
                $response = $app->make('response');
                $response->getBody()->write($echo);
            }
        }

        $this->fresh($response);

        return $response;
    }
}