<?php
namespace Olifant\Kernel;

class Bootstrap
{
    private $app;
    private $boot = false;
    private $providers = [];
    private $configs = [];
    private $commands = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function isBooted()
    {
        return $this->boot;
    }

    public function apply(array $map)
    {
        if (isset($map['providers'])) {
            $this->addServiceProviders($map['providers']);
        }

        if (isset($map['configs'])) {
            $this->addConfigs($map['configs']);
        }

        if (Utils::isCLI() and isset($map['console'])) {
            $this->addCommands($map['console']);
        }
    }

    public function addServiceProvider($provider)
    {
        $this->providers[] = $provider;
        if ($this->isBooted()) {
            $this->app->register(new $provider);
        }
    }

    public function addServiceProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->addServiceProvider($provider);
        }
    }

    private function loadServiceProviders()
    {
        foreach ($this->providers as $provider) {
            if ('Olifant\Service\AppServiceProvider' === $provider) {
                (new $provider)->register($this->app);
            } else {
                $this->app->register(new $provider);
            }
        }
    }

    public function addConfig($config)
    {
        $this->configs[] = $config;
        if ($this->isBooted()) {
            require($config);
        }
    }

    public function addConfigs(array $configs)
    {
        foreach ($configs as $config) {
            $this->addConfig($config);
        }
    }

    private function loadConfigs()
    {
        foreach ($this->configs as $config) {
            require($config);
        }
    }

    public function addCommand($command)
    {
        $this->commands[] = $command;
    }

    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
    }

    public function loadCommands()
    {
        return call_user_func_array([$this->app, 'makes'], $this->commands);
    }

    public function boot()
    {
        if ($this->isBooted()) {
            throw new Exception(':(');
        }

        $this->loadServiceProviders();
        $this->loadConfigs();

        $this->boot = true;
    }
}