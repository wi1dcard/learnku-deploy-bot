<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use Buzz\Browser;
use Buzz\Client\FileGetContents;
use Wi1dcard\LearnkuDeployBot\Utils\LearnkuRequestFactory;
use Wi1dcard\LearnkuDeployBot\Requests\UserEditRequest;
use Nyholm\Psr7\Factory\Psr17Factory;

final class SessionCommand extends LearnkuCommand
{
    protected static $defaultName = 'session';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Check if session valid');
    }

    protected function handle($input, $output)
    {
        $request = new UserEditRequest($this->cookies);

        $response = $this->httpClient->sendRequest($request);

        switch ($response->getStatusCode()) {
            case 200:
                $output->success('Session valid.');
                return 0;
            case 302:
                $output->error('Session has expired or invaild.');
                return 1;
            default:
                $output->error('Unknown HTTP status:' . $response->getStatusCode());
                return 2;
        }
    }
}
