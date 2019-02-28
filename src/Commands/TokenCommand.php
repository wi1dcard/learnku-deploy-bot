<?php

namespace Wi1dcard\LearnkuDeployBot\Commands;

use Wi1dcard\LearnkuDeployBot\Extractors\CsrfTokenExtractor;
use Wi1dcard\LearnkuDeployBot\Requests\UserEditRequest;

final class TokenCommand extends LearnkuCommand
{
    protected static $defaultName = 'token';

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Generate a CSRF token');
    }

    protected function handle($input, $output)
    {
        $request = new UserEditRequest($this->cookies);

        $response = $this->httpClient->sendRequest($request);

        $data = (new CsrfTokenExtractor())->extract($response);

        $output->writeln($data['token']);
    }
}
