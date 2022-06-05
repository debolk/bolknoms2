<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Meal extends ApplicationModel
{
    /**
     * All attributes that can be mass-assigned
     */
    protected $fillable = ['event', 'promoted', 'meal_timestamp', 'locked_timestamp', 'capacity'];
    protected $casts = [
        'meal_timestamp' => 'datetime',
        'locked_timestamp' => 'datetime',
    ];

    /**
     * Relationship: one meal has many registrations
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany(\App\Models\Registration::class)->orderBy('name');
    }

    /**
     * Scope: all meals open for new registrations
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereRaw('locked_timestamp > NOW()');
        })->orderBy('meal_timestamp');
    }

    /**
     * Scope: all meals dated today or later
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('meal_timestamp', '>=', date('Y-m-d'))->orderBy('meal_timestamp');
    }

    /**
     * Scope: all meals dated before today, ordered in reverse date (last one first)
     */
    public function scopePrevious(Builder $query): Builder
    {
        return $query->where('meal_timestamp', '<', date('Y-m-d'))->orderByDesc('meal_timestamp');
    }

    /**
     * Scope: the meal for today, if any
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->where(DB::raw('DATE_FORMAT(`meal_timestamp`, "%Y-%m-%d")'), '=', date('Y-m-d'));
    }

    /**
     * Return the date of this meal in long, Dutch format
     * @return string example "dinsdag 19 mei 2015"
     */
    public function longDate()
    {
        return $this->meal_timestamp->formatLocalized('%A %e %B %Y');
    }

    /**
     * Controls output when an object of the class is printed
     * @return string
     */
    public function __toString()
    {
        $output = $this->longDate();

        if (! empty($this->event)) {
            $output .= ' ('.$this->event.')';
        }

        return $output;
    }

    /**
     * Return whether this meal can be subscribed to
     * @return bool true if the meal is open for registrations, false if not
     */
    public function open_for_registrations()
    {
        return $this->locked_timestamp > Carbon::now();
    }

    public function capacityAvailable(): bool
    {
        if ($this->capacity === null) {
            return true;
        }

        return $this->registrations()->count() + 1 <= $this->capacity;
    }

    /**
     * Returns whether a meal is today
     * @return bool
     */
    public function isToday()
    {
        return $this->meal_timestamp->format('Y-m-d') === date('Y-m-d');
    }

    /**
     * Returns the formatted deadline of this meal
     * @return string
     */
    public function deadline()
    {
        $meal = $this->meal_timestamp->format('Y-m-d');
        $lock = $this->locked_timestamp->format('Y-m-d');

        if ($meal === $lock) {
            return $this->locked_timestamp->format('H:i').' uur';
        } else {
            return $this->locked_timestamp->formatLocalized('%A %e %B %H:%M').' uur';
        }
    }

    /**
     * Return whether the deadline for this meal could be considered 'normal'
     * that is, on the day of the meal and on 15:00.
     * @return bool true if normal
     */
    public function normalDeadline()
    {
        return $this->locked_timestamp->format('Y-m-d H:i') === $this->meal_timestamp->format('Y-m-d 15:00');
    }

    /**
     * Return whether the mealtime for this meal could be considered 'normal'
     * that is, 18:30
     * @return bool true if normal
     */
    public function normalMealTime()
    {
        return $this->meal_timestamp->format('H:i') === '18:30';
    }

    private array $cache;

    private Collection $registrationTimestamps;

    public function registrationsBefore(Carbon $timestamp): int
    {
        if (isset($this->cache[$timestamp->timestamp])) {
            return $this->cache[$timestamp->timestamp];
        }

        if (! isset($this->registrationTimestamps)) {
            $this->registrationTimestamps = $this->registrations->map(function ($r) {
                return $r->created_at->timestamp;
            });
        }

        $result = 0;
        $time = $timestamp->timestamp;
        foreach ($this->registrationTimestamps as $t) {
            $result += $t < $time;
        }

        $this->cache[$timestamp->timestamp] = $result;

        return $result;
    }
}
