<?php
namespace Olifant\Service;

class RequestServiceProvider extends ServiceProvider
{
    public function register($app)
    {
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

        $request = call_user_func_array(['Olifant\Http\ServerRequest','fromGlobals'], $stack);

        $app->instance('request', $request);
    }
}
?>