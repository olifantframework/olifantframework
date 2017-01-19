<?php
namespace Olifant\Console;

use Olifant\Job;
use Olifant\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorld extends Command
{
    protected function configure()
    {
       $this
            ->setName('job')
            ->setDescription('Run job queue')
            ->setHelp("This command allows you to create users...")
            ->setDefinition(
                new InputDefinition([
                    new InputOption('index','i',InputOption::VALUE_OPTIONAL)
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $index = $input->getOption('index');
        if ($index) {
            Job::exec($index);
        } else {
            $expired = Job::getExpired();

            foreach ($expired as $e) {
                $sh = __DIR__ . '/../../../../../bin/console job -i ' . $e;
                $sh .= '> /dev/null 2>/dev/null &';
                Process::set($sh)->run();
            }
        }
    }
}