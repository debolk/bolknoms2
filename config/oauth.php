<?php

return [
    'oauth' => env('OAUTH_ENDPOINT'),
    'callback' => env('OAUTH_CALLBACK'),
    'client' => [
        'id' => env('OAUTH_CLIENT_ID'),
        'secret' => env('OAUTH_CLIENT_SECRET'),
    ],
];
