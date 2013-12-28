<?php

class Meal extends Eloquent
{
    //FIXME not ordered by default on date

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

    /**
     * Scope: all meals dated today or later
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', date('Y-m-d'));
    }

    /**
     * Scope: all meals dated before today, ordered in reverse date (last one first)
     */
    public function scopePrevious($query)
    {
        return $query->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc');
    }

    //FIXME rewrite as scope
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

        //FIXME also do not show when empty? or fix nulls in database
        if ($this->event !== null) {
            $output .= ' ('.$this->event.')';
        }
        return $output;
    }

    /**
     * 
     */
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

    public function deadline()
    {
        return strftime('%H:%M',strtotime($this->locked)).' uur';
    }

    /**
     * Returns whether a meal is being promoted
     * @return boolean
     */
    public function promoted()
    {
        return ($this->promoted === '1');
    }
}
