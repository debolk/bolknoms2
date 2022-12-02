<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;

trait SendsAPIErrors
{
    private function errorResponse(int $httpStatus, string $code, string $title): JsonResponse
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
