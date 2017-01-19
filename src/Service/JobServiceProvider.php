<?php
namespace Olifant\Service;

use Olifant\Kernel\JobManager;

class JobServiceProvider extends ServiceProvider
{
    public $deferred = true;

    public $provides = 'job';

    public function register($app)
    {
        $app->instance('job', new JobManager);
    }
}