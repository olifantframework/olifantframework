<?php
namespace Olifant\Service;

use Noodlehaus\Config;

class SettingsServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $app->bind('settings', function($app) {
            //return new Client;
            // Noodlehaus\Config
        });
    }
}