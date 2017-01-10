<?php
namespace Olifant\Module;

abstract class ModuleBase
{
    protected $requires = [];

    public function getRequired()
    {
        return $this->requires;
    }

    abstract public function register(Application $app);
}