<?php
Olifant\App::config(function (Olifant\Debugger $debugger) {
    //if ('debug' === Olifant\Settings::get('', 'release')) {
        if (Whoops\Util\Misc::isCommandLine()) {
            $handler = new \Whoops\Handler\PlainTextHandler;
        } else if (Whoops\Util\Misc::isAjaxRequest()) {
           $handler = new \Whoops\Handler\JsonResponseHandler;
        } else {
           $handler = new \Whoops\Handler\PrettyPageHandler;
        }

        $debugger->pushHandler($handler);

        $debugger->pushHandler(function($exception, $inspector, $run) {
            if ($exception instanceof Whoops\Exception\ErrorException) {
                $exception = new ErrorException(
                    $exception->getMessage(),
                    $exception->getCode(),
                    E_ERROR,
                    $exception->getFile(),
                    $exception->getLine()
                );
            }

            Olifant\Event::emit('error', [$exception]);
            Olifant\Event::emit(
                get_class($exception),
                [$exception]
            );
        });
    //}
});