<?php

class RequestHelper
{
    /**
     * Outputs CSS-classes of the current route for page detection
     * @return string keywords of the URL, separated by spaces
     */
    public static function url_classes()
    {
        return trim(str_replace('/', ' ', Route::getCurrentRoute()->getPath()));
    }
}
