<?php

namespace App\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Registration extends Model
{
    use SoftDeletes;
    use HasFactory;
    use GeneratesUuid;

    /**
     * All properties that can be mass-assigned
     */
    protected $fillable = ['name', 'email', 'handicap'];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    /**
     * @return BelongsTo<Meal, $this>
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('confirmed', '=', true);
    }

    /**
     * Scope: all unconfirmed registrations
     */
    public function scopeUnconfirmed(Builder $query): Builder
    {
        return $query->where('confirmed', '=', false);
    }

    /**
     * Set the salt and save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
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
        return substr(str_shuffle(md5(microtime())), 0, 10);
    }

    /**
     * Returns the eaters whom have eaten the most in this year (starting 01 September)
     * @return \Illuminate\Support\Collection
     */
    public static function top_ytd()
    {
        $query = self::statistics();

        // Determine last 1-sep
        if (time() > strtotime('01 September')) {
            $query->where('meals.meal_timestamp', '>=', date('Y-m-d', strtotime('01 September')));
        } else {
            $query->where('meals.meal_timestamp', '>=', date('Y-m-d', strtotime('01 September last year')));
        }

        return $query->get();
    }

    /**
     * Returns the eaters whom have eaten the most (in all time)
     * @return \Illuminate\Support\Collection
     */
    public static function top_alltime()
    {
        return self::statistics()->get();
    }

    /**
     * Helper function that abstracts the common logic of self::top_ytd and self::alltime
     * @return \Illuminate\Database\Query\Builder
     */
    private static function statistics()
    {
        $query = DB::table('users');

        $query->select(DB::raw('users.id, users.name, COUNT(registrations.id) as count'));

        $query->leftJoin('registrations', 'users.id', '=', 'registrations.user_id');
        $query->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id');

        $query->whereRaw('meals.meal_timestamp <= NOW()');
        $query->whereNull('meals.deleted_at');
        $query->whereNull('registrations.deleted_at');

        $query->groupBy('users.id', 'users.name');
        $query->orderByDesc('count');

        return $query;
    }
}
