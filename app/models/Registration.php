<?php

class Registration extends Eloquent
{
    /**
     * This model isn't removed from the dabase upon deletion
     */
    use SoftDeletingTrait;

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
        $query->select(DB::raw('name, COUNT(name) as count'));
        $query->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id');
        $query->where('meals.date', '<=', DB::raw('NOW()'));
        $query->whereRaw('registrations.deleted_at IS NULL');
        $query->groupBy('name');
        $query->orderBy('count', 'desc');
        $query->havingRaw('count > 1');
        return $query;
    }

    /**
     * Generate a salt, then save the model
     * @see Illuminate\Databse\Eloquent\Model::save()
     * @param  array    $options
     * @return boolean
     */
    public function save(array $options = array())
    {
        $this->salt = $this->generate_salt();
        return parent::save($options);
    }

    /**
     * Generates a random string of 10 characters in the a-z range, used for securing cancellation requests
     * @return string
     */
    private function generate_salt()
    {
        return substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz',10)),0,10);
    }
}