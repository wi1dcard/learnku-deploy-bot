<?php

namespace Wi1dcard\LearnkuDeployBot\Extractors;

use Psr\Http\Message\ResponseInterface;
use Wi1dcard\LearnkuDeployBot\Exceptions\ExtractorException;
use Wi1dcard\LearnkuDeployBot\Exceptions\BadResponseException;

class ArticleListExtractor implements ExtractResponse
{
    public function extract(ResponseInterface $response): array
    {
        if ($response->getStatusCode() != 200) {
            throw new BadResponseException();
        }

        $text = $response->getBody();

        $regex = '/<a.*?articles\/(?<id>\d+).*?class="title">\s*(?<title>.+?)\s*<\/a>/';

        if (false === preg_match_all($regex, $text, $matches, PREG_SET_ORDER)) {
            throw new ExtractorException();
        }

        return $matches;
    }
}
