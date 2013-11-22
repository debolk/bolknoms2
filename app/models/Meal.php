<?php

class Meal extends Eloquent
{
    protected $fillable = ['date', 'locked', 'event', 'promotion'];

    public function registrations()
    {
        return $this->hasMany('Registration');
    }

    /**
     * Scope: all meals open for new registrations
     */
    public function scopeAvailable($query)
    {
        return $query->where('date', '>', date('Y-m-d'))->orWhere(function($q){
            $q->where('date', '=', date('Y-m-d'))->where('locked', '>=', strftime('%H:%I'));
        })->orderBy('date');
    }

      public static function promotions()
      {
          return self::where('promoted', '=', '1')->get();
      }

    /**
     * Controls output when an object of the class is printed
     * @return string
     */
    public function __toString()
    {
        $output = strftime('%A %d %B %Y', strtotime($this->date));

        if ($this->event !== null) {
            $output .= ' ('.$this->event.')';
        }
        return $output;
    }

    public function open_for_registrations()
    {
      $closing_moment = strtotime($this->date.' '.$this->locked);
      return ($closing_moment > time());
    }

    /**
     * Returns whether a meal is today
     * @return boolean
     */
    public function today()
    {
        return ($this->date === strftime('%Y-%m-%d'));
    }
}