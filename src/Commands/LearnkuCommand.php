<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use Buzz\Client\BuzzClientInterface;
use Buzz\Client\FileGetContents;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

abstract class LearnkuCommand extends Command
{
    protected $cookies;

    /** @var BuzzClientInterface */
    protected $httpClient;

    protected function configure()
    {
        $this->addOption('cookies', 'k', InputOption::VALUE_REQUIRED, 'Learnku web cookies', 'build/cookies.txt');
    }

    abstract protected function handle(InputInterface $input, SymfonyStyle $output);

    protected function execute($input, $output)
    {
        $this->httpClient = new FileGetContents(new Psr17Factory());

        $this->cookies = $input->getOption('cookies');

        if (is_file($this->cookies)) {
            $this->cookies = file_get_contents($this->cookies);
        }

        return $this->handle($input, new SymfonyStyle($input, $output));
    }
}
