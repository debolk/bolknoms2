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
    private static $loggedInMenu = [
        'Aanmelden'     => '/',
        'Spelregels'    => '/spelregels',
        'Disclaimer'    => '/disclaimer',
        'Privacy'       => '/privacy',
        'Top eters'     => '/top-eters',
        'Administratie' => '/administratie',
    ];

    /**
     * Not signed in entries
     * @var array
     */
    private static $loggedOutMenu = [
        'Aanmelden'     => '/',
        'Spelregels'    => '/spelregels',
        'Disclaimer'    => '/disclaimer',
        'Privacy'       => '/privacy',
    ];

    /**
     * Format the main navigation into proper HTML
     * @return string rendered HTML
     */
    public static function show()
    {
        $output = '';
        $entries = OAuth::valid() ? self::$loggedInMenu : self::$loggedOutMenu;

        foreach ($entries as $text => $link) {
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
