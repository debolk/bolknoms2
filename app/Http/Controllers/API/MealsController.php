<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MealsController extends Controller
{
    use SendsAPIErrors;

    public function upcoming(): AnonymousResourceCollection
    {
        return MealResource::collection(Meal::upcoming()->get());
    }
}
