<?php
Olifant\App::config(function(Olifant\Router $router){
    $router->on('/', function(Olifant\Request $request, Olifant\Response $response){

        return 'okay';
    }, [
        'middleware' => ['add:MiddlewareFooBar']
    ]);
});