<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Application;
use App\Http\Requests;
use App\Models\Meal;
use App\Transformers\MealTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class Meals extends Application
{
    /**
     * A list of available meals to which a logged-in user can subscribe
     */
    public function available()
    {
        $fractal = new Manager();
        $meals = Meal::available()->get();
        $resource = new Collection($meals, new MealTransformer);
        return response()->json($fractal->createData($resource)->toArray(), 200);
    }
}
