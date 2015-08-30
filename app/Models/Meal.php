<?php

namespace App\Models;

class Meal extends ApplicationModel
{
    /**
     * All attributes that can be mass-assigned
     */
    protected $fillable = ['date', 'locked', 'event', 'mealtime', 'locked_date'];

    /**
     * Relationship: one meal has many registrations
     * @return Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration')->orderBy('name');
    }

    /**
     * Scope: all meals open for new registrations
     */
    public function scopeAvailable($query)
    {
        return $query->where(function($q){
            $q->where('date', '>', date('Y-m-d'));
            $q->orWhere(function($q){
                $q->where('date', '=', date('Y-m-d'))->where('locked', '>=', strftime('%H:%I'));
            });
        })->orderBy('date');
    }

    /**
     * Scope: all meals dated today or later
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', date('Y-m-d'))->orderBy('date', 'asc');
    }

    /**
     * Scope: all meals dated before today, ordered in reverse date (last one first)
     */
    public function scopePrevious($query)
    {
        return $query->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc');
    }

    /**
     * Scope: the meal for today, if any
     */
    public function scopeToday($query)
    {
        return $query->where('date', '=', date('Y-m-d'));
    }

    /**
     * Return the date of this meal in long, Dutch format
     * @return string example "dinsdag 19 mei 2015"
     */
    public function longDate()
    {
        return strftime('%A %d %B %Y', strtotime($this->date));
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
     * @return boolean true if the meal is open for registrations, false if not
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
    public function isToday()
    {
        return ($this->date === strftime('%Y-%m-%d'));
    }

    /**
     * Returns the formatted deadline of this meal
     * @return string
     */
    public function deadline()
    {
        $output = '';
        if ($this->date !== $this->locked_date) {
            $output = strftime('%A %e %B ', strtotime($this->locked_date));
        }
        return $output.strftime('%H:%M',strtotime($this->locked)).' uur';
    }

    /**
     * Return whether the deadline for this meal could be considered 'normal'
     * that is, on the day of the meal and on 15:00.
     * @return boolean true if normal
     */
    public function normalDeadline()
    {
        return ($this->date === $this->locked_date && $this->locked == '15:00:00');
    }

    /**
     * Return whether the mealtime for this meal could be considered 'normal'
     * that is, 18:30
     * @return boolean true if normal
     */
    public function normalMealTime()
    {
        return ($this->mealtime == '18:30:00');
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
