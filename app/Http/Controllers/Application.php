<?php

namespace App\Http\Controllers;

use App\Http\Helpers\OAuth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class Application extends Controller
{
    use ValidatesRequests;

    /**
     * Common setup to all controllers
     */
    public function __construct()
    {
        // Variables to be included in *every* single view
        View::share('user', OAuth::user());
    }

    /**
     * Show a user-friendly error page
     * @param  integer  $status  http status code
     * @param  string   $message error message to display
     * @return Response
     */
    protected function userFriendlyError($status, $message)
    {
        return response(view($this->layout, [
            'content' => view('errors/index', ['code' => $message]),
            'user' => OAuth::user(),
        ]), $status);
    }
}
