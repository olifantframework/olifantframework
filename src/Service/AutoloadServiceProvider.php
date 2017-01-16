<?php
namespace Olifant\Service;

class AutoloadServiceProvider extends ServiceProvider
{
	private static $loader;

	public static function setLoader($loader)
	{
		self::$loader = $loader;
	}

	public function register($app)
	{
		$app->instance('autoload', self::$loader);
	}
}