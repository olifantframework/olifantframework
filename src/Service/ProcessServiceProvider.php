<?php
namespace Olifant\Service;

use Symfony\Component\Process\Process;

class ProcessServiceProvider extends ServiceProvider
{
    public $deferred = true;

    public $provides = 'process';

    public function register($app)
    {
        $app->bind('process', function() {
            return new Process(null);
        });
    }
}