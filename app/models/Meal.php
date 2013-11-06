<?php

class Meal extends Eloquent
{
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
}