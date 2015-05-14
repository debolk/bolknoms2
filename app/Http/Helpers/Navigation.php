<?php

namespace App\Http\Helpers;

use Request;

/**
 * Allows you to easily set and display flash messages
 */
class Navigation
{
    /**
     * Logged-in menu entries
     * @var array
     */
    private static $menu = [
        ['text' => 'Aanmelden'    , 'url' => '/',              'level' => '0'],
        ['text' => 'Spelregels'   , 'url' => '/spelregels',    'level' => '0'],
        ['text' => 'Top eters'    , 'url' => '/top-eters',     'level' => '1'],
        ['text' => 'Administratie', 'url' => '/administratie', 'level' => '2'],
    ];

    /**
     * Format the main navigation into proper HTML
     * @return string rendered HTML
     */
    public static function show()
    {
        $output = '';

        // Determine which elements to show
        $level = 0;
        if (OAuth::valid()) {
            $level = 1;
            if (OAuth::isBoardMember()) {
                $level = 2;
            }
        }

        foreach (self::$menu as $entry) {
            if ($level >= $entry['level']) {
                $current = (self::isCurrent($entry['url']) ? 'class=current' : '');
                $output .= '<a href="'.$entry['url'].'" '.$current.'>'.$entry['text'].'</a>';
            }
        }

        return $output;
    }

    /**
     * Determine if a given URL is currently on screen
     * @param  string  $link partial URL
     * @return boolean       true if this is the current link
     */
    private static function isCurrent($link)
    {
        $path = Request::path();

        if ($link === '/') {
            return $path === $link;
        }
        else {
            return (strpos(Request::path(), substr($link, 1)) !== false);
        }
    }
}
