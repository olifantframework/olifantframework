<?php
namespace Olifant\Service;

use Whoops\Run;

class DebuggerServiceProvider extends ServiceProvider
{
	public function register($app)
	{
		$whoops = new Run;
		$whoops->register();

		$app->instance('debugger', $whoops);
	}
}