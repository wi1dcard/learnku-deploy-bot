<?php

namespace Wi1dcard\LearnkuDeployBot\Requests;

use Nyholm\Psr7\MessageTrait;
use Nyholm\Psr7\RequestTrait;
use Psr\Http\Message\RequestInterface;
use Nyholm\Psr7\Uri;
use Nyholm\Psr7\Stream;

class LearnkuRequest implements RequestInterface
{
    use MessageTrait;
    use RequestTrait;

    public function __construct(string $cookies, string $method, string $uri, array $data = [])
    {
        $this->setHeaders([
            'Cookie' => $cookies,
        ]);

        $this->method = $method;
        $this->uri = new Uri('https://learnku.com' . $uri);
        $this->stream = Stream::create(
            http_build_query($data)
        );
    }
}
