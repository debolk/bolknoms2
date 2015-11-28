<?php

namespace App\Services;

use Log;
use Validator;
use App\Models\Meal;
use DateTime;

class UpdateMealService extends Service
{
    /**
     * Set the Service
     * @param array $data data for the new Meal
     */
    public function __construct(Meal $meal, array $data)
    {
        $this->meal = $meal;
        $this->data = $data;
    }

    /**
     * Create a new Meal
     * @return App\Models\Meal the newly created meal
     * @throws ValidationException
     */
    public function execute()
    {
        // Validate the resulting input
        $validator = Validator::make($this->data, [
            'meal_timestamp'   => ['date_format:d-m-Y G:i', 'required', 'unique:meals,meal_timestamp,'.$this->meal->id],
            'locked_timestamp' => ['date_format:d-m-Y G:i', 'required', 'after:meal_timestamp'],
        ],[
            'meal_timestamp.date_format'   => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'meal_timestamp.required'      => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'meal_timestamp.unique'        => 'Er is al een maaltijd op deze datum en tijd',
            'locked_timestamp.date_format' => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'locked_timestamp.required'    => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'after:meal_timestamp' => 'De sluitingstijd moet eerder zijn dan het begin van de maaltijd',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->messages());
        }

        // Reformat dates for storage in the database
        $this->data['meal_timestamp']   = DateTime::createFromFormat('d-m-Y G:i', $this->data['meal_timestamp'])->format('Y-m-d G:i:00');
        $this->data['locked_timestamp'] = DateTime::createFromFormat('d-m-Y G:i', $this->data['locked_timestamp'])->format('Y-m-d G:i:00');

        // Save new meal
        $this->meal->update($this->data);
        if ($this->meal->save()) {
            Log::info("Maaltijd geupdate: $this->meal->id|$this->meal->meal_timestamp|$this->meal->event");
            return $this->meal;
        }
        else {
            return null;
        }
    }
}
