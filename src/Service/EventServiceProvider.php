<?php
namespace Olifant\Service;

use Evenement\EventEmitter;

class EventServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $event = new EventEmitter;

        $app->instance('event', $event);
    }
}