<?php

namespace App\Models;

use DB;

class Registration extends ApplicationModel
{
    /**
     * All properties that can be mass-assigned
     */
    protected $fillable = ['name', 'email', 'handicap', 'username', 'confirmed'];

    /**
     * Relationship: a registration belongs to a meal
     * @return Relations\BelongsTo
     */
    public function meal()
    {
        return $this->belongsTo('App\Models\Meal');
    }

    /**
     * Relationship: a registration belongs to a user
     * @return Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Controls output when an object of the class is printed
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Returns the fully Dutch-formatted date of this registration
     * @return string e.g. "maandag 19 mei 2016"
     */
    public function longDate()
    {
        return $this->meal->longDate();
    }

    /**
     * Scope: all confirmed registrations
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', '=', true);
    }

    /**
     * Scope: all unconfirmed registrations
     */
    public function scopeUnconfirmed($query)
    {
        return $query->where('confirmed', '=', false);
    }

    /**
     * Set the salt and save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array())
    {
        // Set the salt
        if ($this->salt === null) {
            $this->salt = self::generateSalt();
        }

        return parent::save($options);
    }

    /**
     * Generates a random string to use as a salt
     * @return string
     */
    private static function generateSalt()
    {
        return substr(str_shuffle(MD5(microtime())), 0, 10);
    }

    /**
     * Returns the eaters whom have eaten the most in this year (starting 01 September)
     * @param int $count the number of entries to retrieve
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function top_ytd()
    {
        $query = self::statistics();

        // Determine last 1-sep
        if (time() > strtotime('01 September')) {
            $query->where('meals.date', '>=', date('Y-m-d', strtotime('01 September')));
        }
        else {
            $query->where('meals.date', '>=', date('Y-m-d', strtotime('01 September last year')));
        }

        return $query->get();
    }

    /**
     * Returns the eaters whom have eaten the most (in all time)
     * @param int $count the number of entries to retrieve
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function top_alltime()
    {
        return self::statistics()->get();
    }

    /**
     * Helper function that abstracts the common logic of self::top_ytd and self::alltime
     * @param int $count the number of entries to retrieve
     * @return Illuminate\Database\Eloquent\Builder
     */
    private static function statistics()
    {
        $query = DB::table('registrations');
        $query->select(DB::raw('name, username, COUNT(username) as count'));
        $query->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id');
        $query->where('meals.date', '<=', DB::raw('NOW()'));
        $query->whereNull('meals.deleted_at');
        $query->whereNull('registrations.deleted_at');
        $query->whereNotNull('username');
        $query->groupBy('username');
        $query->orderBy('count', 'desc');
        return $query;
    }
}
