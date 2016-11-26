<?php
namespace Olifant\Service;

use Olifant\Http\Response;

class ResponseServiceProvider extends ServiceProvider
{
    public $deferred = true;
    public $provides = 'response';

    public function register($app)
    {
        $response = new Response;

        $app->instance('response', $response);
    }
}
?>