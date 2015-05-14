<?php

namespace App\Models;

use DB;

/**
 * The User-class is the local extension of the OAuth2 user.
 * It holds all custom user behaviour and data for Bolknoms
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
}
