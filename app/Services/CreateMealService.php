<?php

namespace App\Services;

use Log;
use Validator;
use DateTime;
use App\Models\Meal;

class CreateMealService extends Service
{
    private $data;

    /**
     * Set the Service
     * @param array $data data for the new Meal
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Create a new Meal
     * @return \App\Models\Meal|null the newly created meal
     * @throws ValidationException
     */
    public function execute()
    {
        // Validate the resulting input
        $validator = Validator::make($this->data, [
            'meal_timestamp'   => ['date_format:d-m-Y G:i', 'required', 'after:now', 'unique:meals'],
            'locked_timestamp' => ['date_format:d-m-Y G:i', 'required', 'after:now', 'before:meal_timestamp']
        ], [
             'meal_timestamp.date_format'   => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'meal_timestamp.required'      => 'De ingevulde maaltijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'meal_timestamp.after'         => 'Je kunt geen maaltijden aanmaken in het verleden',
             'meal_timestamp.unique'        => 'Er is al een maaltijd op deze datum en tijd',
             'locked_timestamp.date_format' => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'locked_timestamp.required'    => 'De ingevulde sluitingstijd is ongeldig (formaat DD-MM-YYYY HH:MM)',
             'locked_timestamp.after'       => 'De deadline voor aanmelding mag niet al geweest zijn',
             'locked_timestamp.before'      => 'De sluitingstijd moet voor het begin van de maaltijd liggen',
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
        $meal = new Meal($this->data);
        if ($meal->save()) {
            return $meal;
        } else {
            return null;
        }
    }
}
