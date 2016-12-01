<?php
namespace Olifant\Service;

use Olifant\Http\Response;
use Olifant\Kernel\Utils;
use Olifant\Kernel\KernelException;

class ResponseServiceProvider extends ServiceProvider
{
    public $deferred = true;
    public $provides = 'response';

    public function register($app)
    {
        if (Utils::isCLI()) {
            return $app->bind('response', function() {
                throw new KernelException(
                    ResponseServiceProvider::class . ' disabled in CLI mode'
                );
            });
        }

        $app->instance('response', new Response);
    }
}