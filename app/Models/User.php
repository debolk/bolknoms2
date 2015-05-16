<?php

namespace App\Models;

use DB;

/**
 * The User-class is the local extension of the OAuth2 user.
 * It holds all custom user behaviour and data for Bolknoms.
 * Note that as users are primarily identified by only their
 * username, they cannot be persisted in the database.
 */
class User
{
    /**
     * Properties of the User
     * @var string
     * @access public
     */
    public $id;
    public $name;
    public $photoURL;

    /**
     * Construct the user
     * @param string $id       the OAuth2 user id
     * @param string $name     the full name of the user
     * @param string $photoURL the full url to the photo of the user
     */
    public function __construct($id, $name, $photoURL)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->photoURL = $photoURL;
    }

    /**
     * Return whether the user is registered to the meal
     * @param  Meal $meal
     * @return boolean       true if registered, false otherwise
     */
    public function registeredFor($meal)
    {
        $query = DB::table('registrations');
        $query->whereNull('deleted_at');
        $query->where('meal_id', '=', $meal->id);
        $query->where('username', '=', $this->id);
        return $query->count();
    }

    /**
     * Return the Registration for this user and meal
     * @param  Meal $meal the meal
     * @return Registration
     */
    public function registrationFor($meal)
    {
        return Registration::where('meal_id', '=', $meal->id)
                            ->where('username', '=', $this->id)
                            ->take(1)
                            ->first();
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
                ->where('username', '=', $this->id)
                ->where('meals.date', '>=', date('Y-m-d', $timestamp))
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
        $registrations = Registration::top_ytd();

        $rank = 0;
        foreach ($registrations as $registration) {
            $rank++;
            if ($registration->username === $this->id) {
                return $rank;
            }
        }

        return null;
    }
}
