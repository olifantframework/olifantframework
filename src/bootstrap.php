<?php
$composer = require __DIR__ . '/../../../autoload.php';
$composer->addPsr4('Olifant\\', __DIR__);
$composer->addPsr4('Olifant\\', __DIR__ . '/Facade');
Olifant\Service\AutoloadServiceProvider::setLoader($composer);

$app = new Olifant\Kernel\Application;
Olifant\Facade::setApp($app);
$bootstrap = new Olifant\Kernel\Bootstrap($app);
$app->instance('bootstrap', $bootstrap);

//$isJob = Olifant\Kernel\Utils::isCLI() and $argv[1] === 'job';

/*if ($isJob) {
     $bootstrap->apply([
        'providers' => [
            'Olifant\Service\AppServiceProvider'
        ]
    ]);
} else {*/
    $bootstrap->apply([
        'providers' => [
            'Olifant\Service\AppServiceProvider',
            'Olifant\Service\AutoloadServiceProvider',
            'Olifant\Service\JobServiceProvider',
            'Olifant\Service\ProcessServiceProvider',
            'Olifant\Service\SettingsServiceProvider',
            'Olifant\Service\EnvServiceProvider',
            'Olifant\Service\EventServiceProvider',
            'Olifant\Service\DebuggerServiceProvider',
            'Olifant\Service\UriServiceProvider'
        ],
        'configs' => [
            __DIR__ . '/Config/Debugger.php',
            __DIR__ . '/Config/Settings.php',
        ]
    ]);

    if (!Olifant\Kernel\Utils::isCLI()) {
        $bootstrap->apply([
            'providers' => [
                'Olifant\Service\RequestServiceProvider',
                'Olifant\Service\ResponseServiceProvider',
                'Olifant\Service\HttpClientServiceProvider',
                'Olifant\Service\RouterServiceProvider'
            ],
            'configs' => [
                __DIR__ . '/Config/Router.php'
            ]
        ]);
    } else {
        $bootstrap->apply([
            'console' => [
                'Olifant\Console\HelloWorld'
            ]
        ]);
    }
//}
//