<?php
namespace Olifant\Service;

use Olifant\Http\Client;

class HttpClientServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $app->bind('http-client', function($app) {
            return new Client;
        });
    }
}