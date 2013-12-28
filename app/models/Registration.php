<?php

class Registration extends Eloquent
{
    //FIXME not auto-ordered on name
    
    protected $fillable = ['name', 'email', 'handicap'];

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

  public static function top_alltime($count = 10)
  {
    return self::statistics($count)->get();
  }

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