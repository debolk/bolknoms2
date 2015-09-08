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
     * Layout for web app
     * @var View
     */
    private $layout;

    /**
     * Setup common logic for any controller
     * @return void
     */
    public function __construct()
    {
        // Set the default layout for pages
        $this->layout = 'application/application';
    }

    /**
     * Wrap the content provided in the default template
     * @param View $view the View file to provide
     */
    protected function setPageContent(\Illuminate\View\View $view)
    {
        return view($this->layout, [
            'content' => $view,
            'javascript' => $this->loadControllerJavascript(),
            'user' => OAuth::user()
        ]);
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
            'content' => view('error/http', ['status' => $status, 'message' => $message]),
            'user' => OAuth::user()
        ]), $status);
    }

    /**
     * Find the associated Javascript for this controller, if it exists
     * @return string javascript filename to load
     */
    private function loadControllerJavascript()
    {
        preg_match('/([a-zA-Z]+)@/iU', \Route::currentRouteAction(), $controller);
        $file = strtolower($controller[1]);

        if (file_exists(dirname(__FILE__) . "/../../../public/javascripts/$file.js")) {
            return $file;
        }
    }
}
