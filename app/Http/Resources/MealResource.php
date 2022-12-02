<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MealResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'meal_time' => $this->meal_timestamp->toIso8601String(),
            'registations_close' => $this->locked_timestamp->toIso8601String(),
            'open_for_registration' => $this->open_for_registrations(),
            'capacity' => $this->capacity,
            'event' => $this->event,
            'current_user_registered' => Auth::user()->registeredFor($this->resource),
        ];
    }
}
