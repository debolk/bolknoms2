<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Navigation;
use App\Http\Helpers\OAuth;
use App\Http\Helpers\ProfilePicture;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class Application extends Controller
{
    use ValidatesRequests;

    /**
     * @var App\Http\Helpers\OAuth
     */
    protected $oauth;

    /**
     * Common setup to all controllers
     */
    public function __construct(OAuth $oauth, Request $request, Navigation $navigation)
    {
        $this->oauth = $oauth;

        // // Variables to be included in *every* single view
        View::share('user', $this->oauth->user());
        View::share('navigation', $navigation);
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
            'user' => $this->oauth->user(),
        ]), $status);
    }
}
