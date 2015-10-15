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
     * The number of registrations of this user for the current year
     * @return integer
     */
    public function numberOfRegistrationsThisYear()
    {
        // Calculate the last 1 september
        $timestamp = mktime(0, 0, 0, 9, 1, date('Y'));
        if ($timestamp > time()) {
            $timestamp = mktime(0, 0, 0, 9, 1, date('Y') - 1);
        }

        return DB::table('registrations')
                ->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id')
                ->where('user_id', '=', $this->id)
                ->where('meals.meal_timestamp', '>=', date('Y-m-d', $timestamp))
                ->whereNull('registrations.deleted_at')
                ->whereNull('meals.deleted_at')
                ->count();
    }

    /**
     * Return the position (#1, #2, etc) of this user in the top-eaters list for this year
     * @return integer or null when not in the list
     */
    public function topEatersPositionThisYear()
    {
        $entries = Registration::top_ytd();

        $rank = 0;
        foreach ($entries as $entry) {
            $rank++;

            if ($entry->id === $this->id) {
                return $rank;
            }
        }

        return null;
    }
}
