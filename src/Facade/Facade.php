<?php
namespace Olifant;

abstract class Facade
{
    protected static $app;

    abstract public static function getKey();

    public static function setApp($app)
    {
        self::$app = $app;
    }

    protected static function getFacadeRoot()
    {
        return self::$app->make(static::getKey());
    }

    public function constant($name)
    {
        $instance = static::getFacadeRoot();

        return constant(get_class($instance) . '::' . $name);
    }

    public function __get($key)
    {
        $instance = static::getFacadeRoot();

        if (property_exists($instance, $key)) {
            return $instance->$key;
        }

        if (method_exists($instance, '__get')) {
            return call_user_func([$instance, '__get'], $key);
        }

        throw new Exception(':(');
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        return call_user_func_array([$instance, $method], $args);
    }

    public function __call($method, $args)
    {
        $instance = static::getFacadeRoot();

        return call_user_func_array([$instance, $method], $args);
    }
}
?>