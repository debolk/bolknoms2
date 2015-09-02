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
        $errors = [[
            'code' => $code,
            'message' => $message,
            'href' => env('APPLICATION_URL').'/api#error_'.$code
        ]];

        return response()->json(['errors' => $errors], $http_code);
    }

    /**
     * Return request validation errors to the client
     * @param  array $messages array of strings of messages
     * @return Response
     */
    public function validationErrors($messages)
    {
        $errors = array_map(function($message){
            return [
                'code'    => 'parameter_unacceptable',
                'message' => $message,
                'href'    => env('APPLICATION_URL').'/api#error_parameter_unacceptable'
            ];
        }, $messages->all());

        return response()->json(['errors' => $errors], 400);
    }
}
