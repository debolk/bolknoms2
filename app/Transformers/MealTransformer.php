<?php

namespace App\Transformers;

use App\Models\Meal;
use League\Fractal\TransformerAbstract;

class MealTransformer extends TransformerAbstract {

    public function transform(Meal $meal)
    {
        return [
            'id' => $meal->id,
            'event' => $meal->event,
            'meal_timestamp' => $meal->meal_timestamp,
            'locked_timestamp' => $meal->locked_timestamp,
        ];
    }
}
