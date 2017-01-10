<?php
namespace Olifant\Kernel;

use Dflydev\DotAccessData\Data;
use Noodlehaus\Config;

class Settings extends Data
{
    public function __construct(array $settings = null)
    {
        parent::__construct($settings);
    }

    public function load($path, $key = null)
    {
        $settings = Config::load($path)->all();

        if (null !== $key) {
            $this->set($key, $settings);

            return $this;
        }

        return new self($settings);
    }
}