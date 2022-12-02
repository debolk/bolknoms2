<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;

class MealsController extends Controller
{
    use SendsAPIErrors;

    public function upcoming()
    {
        return MealResource::collection(Meal::upcoming()->get());
    }
}
