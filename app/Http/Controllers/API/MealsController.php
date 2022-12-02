<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\MealResource;
use App\Models\Meal;

class MealsController extends APIController
{
    public function upcoming()
    {
        return MealResource::collection(Meal::upcoming()->get());
    }
}
