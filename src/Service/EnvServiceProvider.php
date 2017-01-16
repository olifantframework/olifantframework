<?php
namespace Olifant\Service;

use Surface\Surface;

class EnvServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $app->instance('env', new Surface);
    }
}