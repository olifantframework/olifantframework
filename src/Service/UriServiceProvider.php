<?php
namespace Olifant\Service;

use Zend\Diactoros\Uri;

class UriServiceProvider extends ServiceProvider
{
    public $deferred = true;
    public $provides = 'uri';

    public function register($app)
    {
        $app->bind('uri', function($app) {
            return new Uri;
        });
    }
}