<?php
use Olifant\App;
use Olifant\Request;
use Olifant\Response;

use Olifant\HttpClient;

App::config(function(Olifant\Router $router){
    /*
    $router->on('/ping', function(Request $request){
        var_dump($_POST,$_GET,$_SERVER,$_FILES,$_COOKIE);
    });

    $router->on('/', function(Request $request, Response $response){
        $ping = $request
            ->build('http://olifant.web/ping')
            ->withMethod('POST')
            ->withCookie('lets','go')
            ->withRequestParams([
                'ebe' => [
                    'a' => [
                        'b' => 'le&h=a- _+la'
                    ]
                ]
            ])
            ->withFile('lol','/var/www/olifant.zip');

        return HttpClient::send($ping);
    }, [
        'middleware' => ['add:MiddlewareFooBar']
    ]);
    */

    $router->on('/', function(Request $request, Response $response){
        return $request->getClientInfo()->getDevice()->Os();
    });

   //Olifant\Utils\Docblock::genFacadeAutocomplete('response');
});