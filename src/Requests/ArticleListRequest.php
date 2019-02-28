<?php

namespace Wi1dcard\LearnkuDeployBot\Requests;

class ArticleListRequest extends LearnkuRequest
{
    public function __construct($cookies, $page = 1, $user = 'wi1dcard')
    {
        parent::__construct(
            $cookies,
            'GET',
            "/blog/{$user}?page={$page}"
        );
    }
}
