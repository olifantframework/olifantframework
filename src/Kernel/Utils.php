<?php
namespace Olifant\Kernel;

class Utils
{
    public static function isCLI()
    {
        return 'cli' === php_sapi_name();
    }
}