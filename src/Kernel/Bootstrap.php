<?php
namespace Olifant\Kernel;

class Bootstrap
{
    private $app;
    private $boot = false;

    private $providers = [];
    private $configs   = [];
    private $commands  = [];
    private $events    = [];
    private $modules   = [];
    private $jobs      = [];

    private $registeredProviders = [];
    private $registeredCommands  = [];
    private $registeredEvents    = [];
    private $registeredConfigs   = [];
    private $registeredModules   = [];
    private $registeredJobs      = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function isBooted()
    {
        return $this->boot;
    }

    public function apply(array $map)
    {
        foreach ($map as $section => $stack) {
            switch ($section) {
                default:
                    throw new KernelException(
                        'Cannot apply unknown key: %s',
                        $section
                    );

                case 'providers':
                    $this->addService($stack);
                break;

                case 'configs':
                    $this->addConfig($stack);
                break;

                case 'console':
                    $this->addCommand($stack);
                break;

                case 'events':
                    $this->addEventListener($stack);
                break;

                case 'modules':
                    $this->addModule($stack);
                break;

                case 'jobs':
                    $this->addJob($stack);
                break;
            }
        }
    }

    public function isServiceRegistered($service)
    {
        return in_array($service, $this->registeredProviders);
    }

    public function isCommandRegistered($command)
    {
        return in_array($command, $this->registeredCommands);
    }

    public function isEventLoaded($event)
    {
        return in_array($event, $this->registeredEvents);
    }

    public function isConfigLoaded($config)
    {
        return in_array($config, $this->registeredConfigs);
    }

    public function isModuleLoaded($module)
    {
        return in_array($module, $this->registeredModules);
    }

    public function addService($providers)
    {
        foreach ((array) $providers as $provider) {
            if (!in_array($provider, $this->providers)) {
                $this->providers[] = $provider;
                if ($this->isBooted()) {
                    $this->app->register(new $provider);
                }
            }
        }
    }

    private function loadServices()
    {
        foreach ($this->providers as $provider) {
            if ('Olifant\Service\AppServiceProvider' === $provider) {
                (new $provider)->register($this->app);
            } else {
                $this->app->register(new $provider);
            }

            $this->registeredProviders[] = $provider;
        }
    }

    public function addConfig($configs)
    {
        foreach ((array) $configs as $config) {
            if (!in_array($config, $this->configs)) {
                $this->configs[] = $config;
                if ($this->isBooted()) {
                    require($config);
                }
            }
        }
    }

    private function loadConfigs()
    {
        foreach ($this->configs as $config) {
            require($config);
            $this->registeredConfigs[] = $config;
        }
    }

    public function addCommand($commands)
    {
        foreach ((array) $commands as $command) {
            if (!in_array($command, $this->commands)) {
                $this->commands[] = $command;
            }
        }
    }

    public function loadCommands()
    {
        $back = [];
        foreach ($this->commands as $command) {
            $back[] = $this->app->make($command);
            $this->registeredCommands[] = $command;
        }

        return $back;
    }

    public function addEventListener($events)
    {
        foreach ((array) $events as $event) {
            if (!in_array($event, $this->events)) {
                $this->events[] = $event;
                if ($this->isBooted()) {
                    require($event);
                }
            }
        }
    }

    private function loadEvents()
    {
        foreach ($this->events as $event) {
            require($event);
            $this->registeredEvents[] = $event;
        }
    }

    public function addModule($modules)
    {
        foreach ((array) $modules as $module) {
            if (!in_array($module, $this->modules)) {
                $this->modules[] = $module;
            }
        }
    }

    private function loadModules($modules = false)
    {
        if (false === $modules) {
            $modules = $this->modules;
        }

        foreach ($modules as $module) {
            $module = $this->app->make($module);

            if ($required = $module->getRequired()) {
                $this->loadModules((array) $required);
            }

            call_user_func([$module, 'reqister'], $this->app);

            $this->registeredModules[] = get_class($module);
        }
    }

    public function addJob($jobs)
    {
        foreach ((array) $jobs as $job) {
            if (!in_array($job, $this->jobs)) {
                $this->jobs[] = $job;
                if ($this->isBooted()) {
                    require($job);
                }
            }
        }
    }

    public function loadJobs()
    {
        foreach ($this->jobs as $job) {
            require($job);
            $this->registeredJobs[] = $job;
        }
    }

    public function boot()
    {
        if ($this->isBooted()) {
            throw new KernelException('Already booted');
        }

        $this->loadServices();
        $this->loadConfigs();
        $this->loadEvents();
        $this->loadModules();

        //if (Olifant\Kernel\Utils::isCLI()) {
            $this->loadJobs();
        //}

        $this->boot = true;

        \Olifant\App::job('test','* * * * *', function(){
            echo 1;
            sleep(10);
        });
    }
}