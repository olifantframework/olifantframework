<?php
namespace Olifant\Kernel;

class Bootstrap
{
    private static $app;
    private static $boot = false;
    private static $providers = [];
    private static $configs = [];

    public static function setApp($app)
    {
        self::$app = $app;
    }

    public static function isBooted()
    {
        return self::$boot;
    }

    public static function apply(array $map)
    {
        if (isset($map['providers'])) {
            self::addServiceProviders($map['providers']);
        }

        if (isset($map['configs'])) {
            self::addConfigs($map['configs']);
        }
    }

    public static function addServiceProvider($provider)
    {
        self::$providers[] = $provider;
        if (self::isBooted()) {
            self::$app->register(new $provider);
        }
    }

    public static function addServiceProviders(array $providers)
    {
        foreach ($providers as $provider) {
            self::addServiceProvider($provider);
        }
    }

    private static function loadServiceProviders()
    {
        foreach (self::$providers as $provider) {
            if ('Olifant\Service\AppServiceProvider' === $provider) {
                (new $provider)->register(self::$app);
            } else {
                self::$app->register(new $provider);
            }
        }
    }

    public static function addConfig($config)
    {
        self::$configs[] = $config;
        if (self::isBooted()) {
            require($config);
        }
    }

    public static function addConfigs(array $configs)
    {
        foreach ($configs as $config) {
            self::addConfig($config);
        }
    }

    private static function loadConfigs()
    {
        foreach (self::$configs as $config) {
            require($config);
        }
    }

    public static function boot()
    {
        if (self::isBooted()) {
            throw new Exception(':(');
        }

        self::loadServiceProviders();
        self::loadConfigs();

        self::$boot = true;
    }
}