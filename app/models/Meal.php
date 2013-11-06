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
}