<?php

namespace Wi1dcard\LearnkuDeployBot\Requests;

class UserEditRequest extends LearnkuRequest
{
    public function __construct($cookies, $id = 32249)
    {
        parent::__construct(
            $cookies,
            'GET',
            "/users/{$id}/edit"
        );
    }
}
