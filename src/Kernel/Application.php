<?php
namespace Olifant\Kernel;

use Closure;
use Reservoir\Di;
use Olifant\Event;

class Application extends Di
{
    const VENDOR = 'Olifant Framework';
    const VERSION = '0.0.1';

    private static $instantiated = false;

    private $isRun = false;

    public function __construct()
	{
        if (self::$instantiated) {
            throw new KernelException('App already instantiated');
        }

        self::$instantiated = true;

		parent::__construct();
	}

	public function config(Closure $config)
	{
        $this->make($config);

        return $this;
	}

    public function requires($module)
    {
        Bootstrap::loadModules((array) $module);

        return $this;
    }

    public function job($name, $period, Closure $call)
    {
        $this->make('job')->add($name, $period, $call);

        return $this;
    }

    public function console($command, array $args = [])
    {
        return ConsoleApplication::getInstance($this)->exec($command, $args);
    }

	public function run()
	{
        if ($this->isRun) {
            throw new KernelException('App already running');
        }

        $this->isRun = true;

        if (Utils::isCLI()) {
            ConsoleApplication::getInstance($this)->run();
        } else {
            (new HttpApplication)->run($this);
        }
	}
}