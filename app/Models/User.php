<?php

namespace App\Models;

use DB;

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
        return DB::table('registrations')
            ->select('meals.meal_timestamp')
            ->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id')
            ->where('user_id', '=', $this->id)
            ->whereNull('registrations.deleted_at')
            ->whereNull('meals.deleted_at')
            ->get();
    }
}
