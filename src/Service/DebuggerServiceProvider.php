<?php
namespace Olifant\Service;

class DebuggerServiceProvider extends ServiceProvider
{
	public function register($app)
	{
		$whoops = new \Whoops\Run;
		$whoops->register();

		$app->instance('debugger', $whoops);
	}
}
?>