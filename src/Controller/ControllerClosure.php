<?php
namespace Olifant\Controller;

use Closure;

class ControllerClosure extends ControllerBase
{
    public function bind(Closure $closure)
    {
        return Closure::bind($closure, $this, get_class());
    }
}