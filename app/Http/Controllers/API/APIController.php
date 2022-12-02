<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class APIController extends Controller
{
    public function errorResponse(int $httpStatus, string $code, string $title): JsonResponse
    {
        return response()->json([
            'errors' => [
                (object) [
                    'code' => $code,
                    'title' => $title,
                ]
            ],
        ], $httpStatus);
    }
}
