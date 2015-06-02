<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

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
            'javascript' => $this->loadControllerJavascript()
        ]);
    }

    /**
     * Find the associated Javascript for this controller, if it exists
     * @return string javascript filename to load
     */
    private function loadControllerJavascript()
    {
        preg_match('/([a-zA-Z]+)/', \Route::currentRouteAction(), $controller);
        $file = strtolower($controller[1]);
        if (file_exists(dirname(__FILE__) . "/../../../public/javascripts/$file.js")) {
            return $file;
        }
    }
}
