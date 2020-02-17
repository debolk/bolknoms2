<?php

namespace App\Http\Controllers;

use App\Http\Helpers\OAuth;
use App\Http\Helpers\ProfilePicture;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected $oauth;

    /**
     * Common setup to all controllers
     */
    public function __construct(OAuth $oauth)
    {
        $this->oauth = $oauth;
    }

    /**
     * Show a user-friendly error page
     * @param  integer  $status  http status code
     * @param  string   $message error message to display
     * @return \Illuminate\Http\Response
     */
    protected function userFriendlyError($status, $message)
    {
        return response(view('layouts/master', [
            'content' => view('errors/' . $status, ['code' => $message]),
            'user' => $this->oauth->user(),
        ]), $status);
    }

    /**
     * Helper function to construct correctly formatted JSON error responses
     * to AJAX requests
     * @param  integer $httpStatus    HTTP status code to send
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
