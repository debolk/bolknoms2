<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Vacation extends ApplicationModel
{
    protected $fillable = ['start', 'end'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'start', 'end'];

    public function scopeUpcoming(Builder $query, Carbon $date = null) : Builder
    {
        if ($date === null) {
            $date = Carbon::now();
        }

        return $query->orderBy('start', 'asc')->where('start', '>', $date->format('Y-m-d'));
    }

    public function scopeContains(Builder $query, Carbon $date) : Builder
    {
        return $query->where('start', '<=', $date->format('Y-m-d'))
                     ->where('end', '>', $date->format('Y-m-d'));
    }

    /**
     * Determine whether a given date is in a planned vacation period
     */
    public static function inPlannedVacation(Carbon $date) : bool
    {
        return self::where('start', '<=', $date->format('Y-m-d'))
                   ->where('end', '>', $date->format('Y-m-d'))
                   ->count() >= 1;
    }
}
