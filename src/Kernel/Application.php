<?php
namespace Olifant\Kernel;

use Closure;
use Reservoir\Di;
use Olifant\Router;
use Olifant\Request;
use Olifant\Response;
use Relay\RelayBuilder;
use Zend\Diactoros\Response\SapiEmitter;

class Application
{
	protected $di;
    protected $loaded = false;
    protected $providers = [];
    protected $configs = [];

    public function __construct()
	{
		$this->di = new Di;
	}

    public function __call($method, array $args)
    {
        if (method_exists($this->di, $method)) {
            return call_user_func_array([$this->di, $method], $args);
        }
    }

	public function config(Closure $config)
	{
        $this->make($config);

        return $this;
	}

	public function run()
	{
        $path = Request::getUri()->getPath();
        $route = Router::go($path);

        $this->instance('route', $route);

        $callback = $route->getCallback();

        $queue = [
            /*function($req,$res,$next){
                $res = $res->withHeader('Ebeleh', 'ae');

                return $next($req,$res);
            },
            function($req,$res,$next){
                $res->getBody()->write('foo-');

                return $next($req,$res);
            },
            function($req,$res,$next){
                $res = $next($req,$res);
                $res->getBody()->write('-bar');

                return $res;
            },*/
            new \Olifant\Middleware\Application($callback)
        ];

        $relayBuilder = new RelayBuilder;
        $relay = $relayBuilder->newInstance($queue);
        $response = $relay(
            $this->make('request'),
            $this->make('response')
        );

        (new SapiEmitter)->emit($response);
	}
}
?>