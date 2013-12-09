<?php

class ApplicationController extends Controller
{
    public function __construct()
    {
      $this->layout = View::make('layouts/application');

      $this->promotions();
      $this->top_eaters();
    }

    public function promotions()
    {
      //FIXME only show upcoming promotions
      $this->layout->promoted_meals = View::make('layouts/_promotions', ['meals' => Meal::promotions()]);
    }

    private function top_eaters()
    {
      $this->layout->top_eaters = View::make('layouts/_top', [
        'top_alltime' => Registration::top_alltime(),
        'top_ytd' => Registration::top_ytd(),
      ]);
    }

    protected function authenticate()
    {
        // If no password is supplied, force the user to authenticate
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Bolknoms"');
            header('HTTP/1.0 401 Unauthorized');
            echo View::make('error/403');
            exit;
        }
        else {
            // If a credentials are supplied, attempt to authenticate
            $username = Config::get('app.administration.username');
            $password = Config::get('app.administration.password');
            if ($_SERVER['PHP_AUTH_USER'] === $username && $_SERVER['PHP_AUTH_PW'] === $password) {
                return;
            }
            else {
                App::abort(403, "Credentials invalid");
            }
        }
    }
}
