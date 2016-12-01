<?php
Olifant\App::config(function(Olifant\Router $router){
    $router->on('/', function(Olifant\Request $request, Olifant\Response $response){
        /*$client = new Client;
        $request = new \Olifant\Http\ClientRequest('http://ibusiness.ru/blog/money/42503');


        $request = $request->withRequestParams([
            'utm_source'   => 'vk',
            'utm_medium'   => 'cpc',
            'utm_campaign' => 'lentach'
        ]);

        $promise = $client->sendAsync($request)
            ->then(function ($response) {
                echo $response->getBody();
            })
            ->otherwise(function(){
                dump('pp',func_get_args());
            });

        $promise->wait();*/

        Olifant\App::ebeleh();

        return '123';
    }, [
        'middleware' => ['add:MiddlewareFooBar']
    ]);
});