<?php
namespace Olifant\Utils;

use ReflectionClass;

class ClassDump
{
    public static function dump($instance)
    {
        $class = new ReflectionClass($instance);
        $methods = $class->getMethods();

        $methods = array_filter($methods, function($method){
            return $method->isPublic();
        });

        return $methods;
    }
}
?>