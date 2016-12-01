<?php
namespace Olifant\Service;

use Olifant\Kernel\Utils;
use Olifant\Http\ServerRequest;
use Olifant\Kernel\KernelException;

class RequestServiceProvider extends ServiceProvider
{
    public function register($app)
    {
        if (Utils::isCLI()) {
            return $app->bind('request', function() {
                throw new KernelException(
                    RequestServiceProvider::class . ' disabled in CLI mode'
                );
            });
        }

        $stack = [
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['method']) and $_POST['method']) {
                $stack[0]['REQUEST_METHOD'] = $_POST['method'];
            }
        }

        $request = call_user_func_array(
            [ServerRequest::class,'fromGlobals'],
            $stack
        );

        $app->instance('request', $request);
    }
}