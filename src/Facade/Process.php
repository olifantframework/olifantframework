<?php
namespace Olifant;

class Process extends Facade
{
    public static function getKey()
    {
        return 'process';
    }

    public static function set($command)
    {
        return self::getFacadeRoot()->setCommandLine($command);
    }
}