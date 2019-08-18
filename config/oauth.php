<?php

return [
    'endpoint' => env('OAUTH_ENDPOINT', 'https://auth.debolk.nl/'),
    'callback' => env('OAUTH_CALLBACK', 'http://noms.debolk.nl/oauth/'),
    'client' => [
        'id' => env('OAUTH_CLIENT_ID'),
        'secret' => env('OAUTH_CLIENT_SECRET'),
    ],
];
