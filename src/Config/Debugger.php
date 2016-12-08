<?php
Olifant\App::config(function (Olifant\Debugger $debugger) {
	if (Whoops\Util\Misc::isCommandLine()) {
        $handler = new \Whoops\Handler\PlainTextHandler;
    } else if (Whoops\Util\Misc::isAjaxRequest()) {
	   $handler = new \Whoops\Handler\JsonResponseHandler;
	} else {
		$handler = new \Whoops\Handler\PrettyPageHandler;
	}

	$debugger->pushHandler($handler);
});