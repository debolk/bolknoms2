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
        ['text' => 'Aanmelden', 'action' => 'Register@index', 'icon' => 'calendar', 'level' => '0'],
        ['text' => 'Spelregels', 'action' => 'Page@spelregels', 'icon' => 'file-text-o', 'level' => '0'],
        ['text' => 'Top eters', 'action' => 'Top@index', 'icon' => 'trophy', 'level' => '1'],
        ['text' => 'Mijn profiel', 'action' => 'Profile@index', 'icon' => 'user', 'level' => '1'],
        ['text' => 'Administratie', 'action' => 'Administration\Dashboard@index', 'icon' => 'wrench', 'level' => '2', 'submenu' => [
            ['text' => 'Maaltijden', 'action' => 'Administration\Meals@index', 'icon' => 'cutlery'],
            ['text' => 'Gebruikers', 'action' => 'Administration\Users@index', 'icon' => 'users'],
        ]],
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
                $entry['current'] = self::isCurrent($entry['action']);
                $output .= view('layouts/_nav_item')->with($entry);
            }
        }

        return $output;
    }

    /**
     * Determine if a given action is currently on screen
     * @param  string  $link partial action
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
