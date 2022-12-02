<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StartController extends Controller
{
    public function __invoke(Request $request)
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
