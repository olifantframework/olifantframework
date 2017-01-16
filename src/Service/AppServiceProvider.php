<?php
namespace Olifant\Service;

class AppServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $app->instance('app', $app);
    }
}