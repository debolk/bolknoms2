<?php

class Registration extends Eloquent
{
  public static function top_ytd($count = 10)
  {
    $query = DB::table('registrations');
    $query->select(DB::raw('name, COUNT(name) as count'));
    $query->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id');
    $query->where('meals.date', '<=', 'NOW()');
    $query->groupBy('name');
    $query->orderBy('count');
    $query->take($count);

    // Determine last 1-sep
    if (time() > strtotime('01 September')) {
      $query->where('meals.date', '>=', date('Y-m-d', strtotime('01 September')));
    }
    else {
      $query->where('meals.date', '>=', date('Y-m-d', strtotime('01 September last year')));
    }

    return $query->get();
  }

  public static function top_alltime($count = 10)
  {
    return DB::table('registrations')->select(DB::raw('name, COUNT(name) AS count'))->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id')->where('meals.date', '<=', 'NOW()')->groupBy('name')->orderBy('count')->take($count)->get();
  }
}