<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Helpers\OAuth;

class Application extends Controller
{
    use DispatchesCommands, ValidatesRequests;

    /**
     * Show a user-friendly error page
     * @param  integer  $status  http status code
     * @param  string   $message error message to display
     * @return Response
     */
    protected function userFriendlyError($status, $message)
    {
        return response(view($this->layout, [
            'content' => view('error/http', compact('status', 'message')),
            'user' => OAuth::user()
        ]), $status);
    }
}
