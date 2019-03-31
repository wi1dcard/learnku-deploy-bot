<?php

namespace Wi1dcard\LearnkuDeployBot\Requests;

class SubmitChangesRequest extends LearnkuRequest
{
    public function __construct($cookies, $csrfToken, $articleId, $title, $content, $tags)
    {
        parent::__construct(
            $cookies,
            'POST',
            "/topics/{$articleId}",
            [
                '_method' => 'PATCH',
                '_token' => $csrfToken,
                'category_id' => 8, // May represents blogs.
                'title' => $title,
                'tags_string' => $tags,
                'body' => $content,
                'reason' => '',
                'community_id' => 1, // May represents laravel-china.
            ]
        );
    }
}
