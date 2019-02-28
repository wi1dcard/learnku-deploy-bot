<?php

namespace Wi1dcard\LearnkuDeployBot\Extractors;

use Psr\Http\Message\ResponseInterface;

interface ExtractResponse
{
    public function extract(ResponseInterface $response): array;
}
