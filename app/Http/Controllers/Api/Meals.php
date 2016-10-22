<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Meals extends Controller
{
    /**
     * A list of available meals to which a logged-in user can subscribe
     */
    public function available()
    {
        return response()->json(Meal::available()->get(), 200);
    }
}
