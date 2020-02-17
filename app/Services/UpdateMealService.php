<?php

namespace App\Services;

use Log;
use Validator;
use App\Models\Meal;
use DateTime;

class UpdateMealService extends Service
{
    private $meal;
    private $data;

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
     */
    public function execute(): ?Meal
    {
        // Validate the resulting input
        $validator = Validator::make($this->data, [
            'meal_timestamp'   => ['date_format:d-m-Y G:i', 'required', 'unique:meals,meal_timestamp,' . $this->meal->id],
            'locked_timestamp' => ['date_format:d-m-Y G:i', 'required', 'before:meal_timestamp'],
        ], [
            'meal_timestamp.date_format'   => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'meal_timestamp.required'      => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'meal_timestamp.unique'        => 'Er is al een maaltijd op deze datum en tijd',
            'locked_timestamp.date_format' => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'locked_timestamp.required'    => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
            'locked_timestamp.before'      => 'De sluitingstijd moet eerder zijn dan het begin van de maaltijd',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        // Reformat dates for storage in the database
        $mealTime = DateTime::createFromFormat('d-m-Y G:i', $this->data['meal_timestamp']);
        $lockedTime = DateTime::createFromFormat('d-m-Y G:i', $this->data['locked_timestamp']);
        if (!$mealTime || !$lockedTime) {
            throw new \Exception('Unparseable timestamp format passed through validation, but could not be parsed');
        }
        $this->data['meal_timestamp'] = $mealTime->format('Y-m-d G:i:00');
        $this->data['locked_timestamp'] = $lockedTime->format('Y-m-d G:i:00');

        // Save new meal
        $this->meal->update($this->data);
        if ($this->meal->save()) {
            Log::info("Maaltijd geupdate: $this->meal->id|$this->meal->meal_timestamp|$this->meal->event");
            return $this->meal;
        } else {
            return null;
        }
    }
}
