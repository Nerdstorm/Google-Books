<?php

namespace tests\Nerdstorm;

class Config
{
    const API_KEY = 'AIzaSyBWoCaww-UoB3VbN4QeCV2ESqqD5sD8PTA';

    public static function guzzleOpts()
    {
        return [
            'request.options' => [
                'proxy' => null,
            ],
        ];
    }
}