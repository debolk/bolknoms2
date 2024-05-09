<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \App\Models\Meal
 */
class MealResource extends JsonResource
{
    public function toArray($request)
    {
        $registered = Auth::user()->registeredFor($this->resource);
        $links = [(object) ['rel' => 'user.registration', 'uri' => null]];
        if ($registered) {
            $links[0]->uri = route('api.meals.registrations.destroy', [
                $this->uuid,
                Auth::user()->registrationFor($this->resource)->uuid,
            ]);
        }

        return [

            'data' => [
                'id' => $this->uuid,
                'meal_time' => $this->meal_timestamp->toIso8601String(),
                'registations_close' => $this->locked_timestamp->toIso8601String(),
                'open_for_registration' => $this->open_for_registrations(),
                'capacity' => $this->capacity,
                'event' => $this->event,
                'current_user_registered' => $registered,
            ],

            'links' => $links,
        ];
    }
}
