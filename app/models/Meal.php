<?php

class Meal extends Eloquent
{
      public static function promotions()
      {
          return self::where('promoted', '=', '1');
      }
}