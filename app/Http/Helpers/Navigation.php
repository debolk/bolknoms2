<?php

namespace App\Http\Helpers;

use Request;

/**
 * Allows you to easily set and display flash messages
 */
class Navigation
{
    /**
     * The list of all menu entries
     * @var array
     */
    private static $entries = [
        'Aanmelden'       => '/',
        'Mijn maaltijden' => '/mijnmaaltijden',
        'Top eters'       => '/top-eters',
        'Administratie'   => '/administratie',
        'Disclaimer'      => '/disclaimer',
        'Privacy'         => '/privacy',
    ];

    /**
     * Format the main navigation into proper HTML
     * @return string rendered HTML
     */
    public static function show()
    {
        $output = '';

        foreach (self::$entries as $text => $link) {
            $current = (self::isCurrent($link) ? 'class=current' : '');
            $output .= "<a href=\"$link\" $current>$text</a>";
        }

        return $output;
    }

    /**
     * Determine if a given URL is currently on screen
     * @param  string  $link partial URL
     * @return boolean       true if this is the current link
     */
    public static function isCurrent($link)
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
