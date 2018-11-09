<?php

namespace App\Models;

use Carbon\Carbon;

class Vacation extends ApplicationModel
{
    protected $fillable = ['start', 'end'];

    /**
     * Determine whether a given date is in a planned vacation period
     */
    public static function inPlannedVacation(Carbon $date) : bool
    {
        $dateString = $date->format('Y-m-d');

        return self::where('start', '<=', $dateString)
                   ->where('end', '>', $dateString)
                   ->count() >= 1;
    }
}
