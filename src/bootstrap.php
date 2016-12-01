<?php
$composer = require __DIR__ . '/../../../autoload.php';
$composer->addPsr4('Olifant\\', __DIR__);
$composer->addPsr4('Olifant\\', __DIR__ . '/Facade');
Olifant\Service\AutoloadServiceProvider::setLoader($composer);

$app = new Olifant\Kernel\Application;
$bootstrap = new Olifant\Kernel\Bootstrap($app);
$app->instance('bootstrap', $bootstrap);
Olifant\Facade::setApp($app);

$bootstrap->apply([
    'providers' => [
        'Olifant\Service\AppServiceProvider',
        'Olifant\Service\AutoloadServiceProvider',
        'Olifant\Service\EventServiceProvider',
        'Olifant\Service\DebuggerServiceProvider',
        'Olifant\Service\RequestServiceProvider',
        'Olifant\Service\ResponseServiceProvider',
        'Olifant\Service\UriServiceProvider',
        'Olifant\Service\RouterServiceProvider'
    ],
    'configs' => [
        __DIR__ . '/Config/Debugger.php',
        __DIR__ . '/Config/Router.php'
    ],
    'console' => [
        'Olifant\Console\HelloWorld'
    ]
]);