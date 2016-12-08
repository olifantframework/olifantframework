<?php
namespace Olifant\Kernel;

class Settings
{
    private $storage = array();

    public function __construct(array $storage = [])
    {
        $this->storage = $storage;
    }

    public function with(array $storage)
    {
        $this->storage += $storage;
    }

    public function get($key, $default = null)
    {

    }

    public function set($key, $value)
    {

    }

    public function has($key)
    {

    }

    public function del($key)
    {

    }

    public function all()
    {
        return $this->storage;
    }

    public function load($path, $key = null)
    {

    }
}