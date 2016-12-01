<?php
namespace Olifant;

abstract class Facade
{
    protected static $app;

    public static function getKey()
    {
        throw new Exception('Facade::getKey');
    }

    public static function setApp($app)
    {
        self::$app = $app;
    }

    protected static function getFacadeRoot()
    {
        return self::$app->make(static::getKey());
    }

    public static function constant($name)
    {
        $instance = static::getFacadeRoot();

        return constant(get_class($instance) . '::' . $name);
    }

    private static function invoke($method, array $args = [])
    {
        $instance = static::getFacadeRoot();
        if (method_exists($instance, $method)
            or method_exists($instance, '__call')) {
            return call_user_func_array([$instance, $method], $args);
        } else {
            $trace = debug_backtrace()[2];

            $facadeException = new FacadeException;
            $facadeException->setMessage(sprintf(
                'Method %s does not exist in %s',
                $trace['function'],
                $trace['class']
            ));
            $facadeException->setLine($trace['line']);
            $facadeException->setFile($trace['file']);

            throw $facadeException;
        }
    }

    public static function __callStatic($method, $args)
    {
        return static::invoke($method, $args);
    }

    public function __call($method, $args)
    {
        return static::invoke($method, $args);
    }
}