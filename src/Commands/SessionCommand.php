<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use GuzzleHttp\Cookie\SetCookie;
use Symfony\Component\Console\Input\InputOption;
use Wi1dcard\LearnkuDeployBot\Requests\UserEditRequest;

final class SessionCommand extends LearnkuCommand
{
    protected static $defaultName = 'session';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Check if session valid, generate new cookie.')
            ->addOption('refresh', 'r', InputOption::VALUE_NONE, 'Generate new cookies.');
    }

    protected function handle($input, $output)
    {
        $request = new UserEditRequest($this->cookies);

        $response = $this->httpClient->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                break;
            case 302:
                throw new \RuntimeException('Session has expired or invaild.');
                return 1;
            default:
                throw new \RuntimeException('Unknown HTTP status:' . $response->getStatusCode());
                return 2;
        }

        if (!$input->getOption('refresh')) {
            $output->writeln('Session valid.');
            return 0;
        }

        $cookieHeaders = $response->getHeader('Set-Cookie');
        foreach ($cookieHeaders as $header) {
            $setCookie = SetCookie::fromString($header);
            $cookieString = sprintf(
                '%s=%s; ',
                $setCookie->getName(),
                $setCookie->getValue()
            );
            $output->write($cookieString);
        }

        $output->newLine();
    }
}
