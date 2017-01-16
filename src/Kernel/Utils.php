<?php
namespace Olifant\Kernel;

class Utils
{
    public static function isCLI()
    {
        return 'cli' === php_sapi_name();
    }

    public static function logo()
    {
        /**
        '
             _  _ \
              ( \--,/)
          ,---\ ` '_/
         /( ___'--/`
          |_|\ |_|\ olifant';
        */
    }
}