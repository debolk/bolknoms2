<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Route;
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
        $menu_entries = self::$menu;
        $output = '';

        // Determine which elements to show
        $level = 0;
        if (OAuth::valid()) {
            $level = 1;
            if (OAuth::isBoardMember()) {
                $level = 2;
            }
        }

        // Set a flag to indicate the current route
        for ($i=0; $i < sizeof($menu_entries); $i++) {
            $menu_entries[$i]['current'] = self::isCurrent($menu_entries[$i]['action']);

            // iterate over submenu's
            if (isset($menu_entries[$i]['submenu'])) {
                for ($j=0; $j < sizeof($menu_entries[$i]['submenu']); $j++) {
                    $menu_entries[$i]['submenu'][$j]['current'] = self::isCurrent($menu_entries[$i]['submenu'][$j]['action']);
                }
            }
        }

        // Render all navigation items
        foreach ($menu_entries as $entry) {
            if ($level >= $entry['level']) {
                $output .= view('layouts/_nav_item')->with($entry);
            }
        }

        return $output;
    }

    /**
     * Determine if a given action is currently on screen
     * @param  string  $action
     * @return boolean
     */
    private static function isCurrent($action)
    {
        // Exceptional case for HTTP 404 errors (which have no route)
        if (Route::current() === null) {
            return false;
        }

        return Route::current()->getActionName() === 'App\Http\Controllers\\' . $action;
    }
}
