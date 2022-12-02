<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class APIDocumentation extends Controller
{
    public function show(): View
    {
        return view('apidocs/index');
    }
}
