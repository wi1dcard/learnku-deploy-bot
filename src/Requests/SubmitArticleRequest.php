<?php

namespace Wi1dcard\LearnkuDeployBot\Requests;

class SubmitArticleRequest extends LearnkuRequest
{
    public function __construct($cookies, $csrfToken, $title, $content)
    {
        parent::__construct(
            $cookies,
            'POST',
            "/articles",
            [
                '_token' => $csrfToken,
                'category_id' => 8, // May represents blogs.
                'title' => $title,
                'tags_string' => '',
                'body' => $content,
                'community_id' => 1, // May represents laravel-china.
            ]
        );
    }
}
