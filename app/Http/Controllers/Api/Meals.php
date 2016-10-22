<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Application;
use App\Http\Requests;
use App\Models\Meal;
use Illuminate\Http\Request;

class Meals extends Application
{
    /**
     * A list of available meals to which a logged-in user can subscribe
     */
    public function available()
    {
        return response()->json(Meal::available()->get(), 200);
    }
}
