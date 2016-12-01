<?php
namespace Olifant\Kernel;

use Symfony\Component\Console\Application as Console;

class ConsoleApplication extends Console
{
    public function __construct($name, $version)
    {
        parent::__construct($name, $version);
    }
}