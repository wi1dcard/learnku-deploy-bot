<?php

namespace Wi1dcard\LearnkuDeployBot\Extractors;

use Psr\Http\Message\ResponseInterface;
use Wi1dcard\LearnkuDeployBot\Exceptions\ExtractorException;
use Wi1dcard\LearnkuDeployBot\Exceptions\BadResponseException;

class CsrfTokenExtractor implements ExtractResponse
{
    public function extract(ResponseInterface $response): array
    {
        $text = $response->getBody();

        $regex = '/<meta name="csrf-token" content="(?<token>.+?)">/';

        if (false === preg_match($regex, $text, $matches)) {
            throw new ExtractorException();
        }

        return $matches;
    }
}
