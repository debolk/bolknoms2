<?php

namespace App\Models;

use DB;

/**
 * The User-class is the local extension of the OAuth2 user.
 * It holds all custom user behaviour and data for Bolknoms.
 * Note that as users are primarily identified by only their
 * username, they cannot be persisted in the database.
 */
class User extends ApplicationModel
{
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration')->orderBy('name');
    }

    /**
     * Return whether the user is registered to the meal
     * @param  Meal $meal
     * @return boolean       true if registered, false otherwise
     */
    public function registeredFor($meal)
    {
        return $this->registrations()->where(['meal_id' => $meal->id])->count() > 0;
    }

    /**
     * Return the Registration for this user and meal
     * @param  Meal $meal the meal
     * @return Registration
     */
    public function registrationFor($meal)
    {
        return $this->registrations()->where(['meal_id' => $meal->id])->first();
    }

    /**
     * Returns a list of dates on which you've joined a meal
     * @return array
     */
    public function dateList()
    {
        $query = DB::table('registrations')
                    ->select('meals.meal_timestamp')
                    ->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id')
                    ->where('user_id', '=', $this->id)
                    ->whereNull('registrations.deleted_at')
                    ->whereNull('meals.deleted_at');

        // Determine last 1-sep
        if (time() > strtotime('01 September')) {
            $query->where('meals.meal_timestamp', '>=', date('Y-m-d', strtotime('01 September')));
        }
        else {
            $query->where('meals.meal_timestamp', '>=', date('Y-m-d', strtotime('01 September last year')));
        }

        return $query->get();
    }
}
