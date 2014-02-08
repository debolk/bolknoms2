<?php

class Registration extends Eloquent
{
    //FIXME not auto-ordered on name
    
    /**
     * All properties that can be mass-assigned
     */
    protected $fillable = ['name', 'email', 'handicap'];

    /**
     * Relationship: a registration belongs to a meal
     * @return Relations\BelongsTo
     */
    public function meal()
    {
        return $this->belongsTo('Meal');
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
     * Returns the eaters whom have eaten the most in this year (starting 01 September)
     * @param int $count the number of entries to retrieve
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function top_ytd($count = 10)
    {
        $query = self::statistics($count);

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
    public static function top_alltime($count = 10)
    {
        return self::statistics($count)->get();
    }

    /**
     * Helper function that abstracts the common logic of self::top_ytd and self::alltime
     * @param int $count the number of entries to retrieve
     * @return Illuminate\Database\Eloquent\Builder
     */
    private static function statistics($count)
    {
        $query = DB::table('registrations');
        $query->select(DB::raw('name, COUNT(name) as count'));
        $query->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id');
        $query->where('meals.date', '<=', 'NOW()');
        $query->groupBy('name');
        $query->orderBy('count', 'desc');
        $query->take($count);
        return $query;
    }
}