<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Create a fully-formatted fatal error
     * @param  integer $http_code HTTP status code
     * @param  string  $code      application specific error code
     * @param  string  $message   detailed error message
     * @return Response
     */
    public function fatalError($http_code, $code, $message)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'href' => env('APPLICATION_URL').'/api#error_'.$code
        ], $http_code);
    }
}
