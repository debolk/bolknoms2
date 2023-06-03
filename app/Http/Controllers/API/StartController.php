<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class StartController extends Controller
{
    public function __invoke(): array
    {
        return [
            'links' => [
                (object) [
                    'rel' => 'meals.upcoming',
                    'uri' => route('api.meals.upcoming'),
                ],
            ],
        ];
    }
}
