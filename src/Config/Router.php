<?php
use Olifant\App;
use Olifant\Request;
use Olifant\Response;

use Olifant\HttpClient;

App::config(function(Olifant\Router $router){
    $router->on('/', function(Request $request, Response $response){
        register_shutdown_function(function(){
            var_dump(error_get_last());
        });
    });
});