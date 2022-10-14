<?php

namespace App\Http\Controllers;

use App\Http\Helpers\OAuth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Show a user-friendly error page
     */
    protected function userFriendlyError(int $status, string $message): Response
    {
        return response(view('layouts/master', [
            'content' => view('errors/' . $status, ['code' => $message]),
            'user' => Auth::user(),
        ]), $status);
    }

    /**
     * Helper function to construct correctly formatted JSON error responses
     * to AJAX requests
     * @param  int $httpStatus    HTTP status code to send
     * @param  string  $internalError descriptive error code, e.g. meal_not_found
     * @param  string  $message       line of text to explain error state to end users
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ajaxError($httpStatus, $internalError, $message)
    {
        return response()->json([
            'error' => $internalError,
            'error_details' => $message,
        ], $httpStatus);
    }
}
