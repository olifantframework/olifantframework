<?php
namespace Olifant\Kernel;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Application as Console;

class ConsoleApplication extends Console
{
    private static $instance;

    public function __construct($name, $version)
    {
        parent::__construct($name, $version);
    }

    public static function getInstance($app)
    {
        if (null === self::$instance) {
            self::$instance = new self(
                Application::VENDOR,
                Application::VERSION
            );

            $commands = $app->make('bootstrap')->loadCommands();
            self::$instance->addCommands($commands);
        }

        return self::$instance;
    }

    public function exec($command, array $args = [])
    {
        $args = ['command' => $command] + $args ?: [];

        $input = new ArrayInput($args);
        $output = new BufferedOutput();

        $this->setAutoExit(false);
        $this->run($input, $output);

        return $output->fetch();
    }
}