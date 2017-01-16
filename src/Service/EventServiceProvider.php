<?php
namespace Olifant\Service;

use Evenement\EventEmitter;

class EventServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $app->instance('event', new EventEmitter);
    }
}