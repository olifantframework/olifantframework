<?php
namespace Olifant\Service;

use Olifant\Kernel\Settings;

class SettingsServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        $app->instance('settings',  new Settings);
    }
}