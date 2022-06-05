<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/**
 * Renders the menu
 */
class Navigation
{
    private $oauth;

    public function __construct(OAuth $oauth)
    {
        $this->oauth = $oauth;
    }

    /**
     * Logged-in menu entries
     * @var array
     */
    private $menu = [
        ['text' => 'Aanmelden', 'action' => 'Register@index', 'icon' => 'calendar', 'level' => '0'],
        ['text' => 'Spelregels', 'action' => 'Page@spelregels', 'icon' => 'file-text-o', 'level' => '0'],
        ['text' => 'Top eters', 'action' => 'Top@index', 'icon' => 'trophy', 'level' => '1'],
        ['text' => 'Mijn profiel', 'action' => 'Profile@index', 'icon' => 'user', 'level' => '1'],
        ['text' => 'Administratie', 'action' => 'Administration\Dashboard@index', 'icon' => 'wrench', 'level' => '2', 'submenu' => [
            ['text' => 'Maaltijden', 'action' => 'Administration\Meals@index', 'icon' => 'cutlery'],
            ['text' => 'Gebruikers', 'action' => 'Administration\Users@index', 'icon' => 'users'],
            ['text' => 'Vakanties', 'action' => 'Administration\Vacations@index', 'icon' => 'plane'],
        ]],
    ];

    /**
     * Format the main navigation into proper HTML
     */
    public function show(): string
    {
        $menu_entries = $this->menu;
        $output = '';

        // Determine which elements to show
        $level = 0;
        if ($this->oauth->valid()) {
            $level = 1;
            if ($this->oauth->isBoardMember()) {
                $level = 2;
            }
        }

        // Set a flag to indicate the current route
        for ($i = 0; $i < count($menu_entries); $i++) {
            $menu_entries[$i]['current'] = $this->isCurrent($menu_entries[$i]['action']);

            // iterate over submenu's
            if (isset($menu_entries[$i]['submenu'])) {
                for ($j = 0; $j < count($menu_entries[$i]['submenu']); $j++) {
                    $menu_entries[$i]['submenu'][$j]['current'] = $this->isCurrent($menu_entries[$i]['submenu'][$j]['action']);
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
     */
    private function isCurrent(string $action): bool
    {
        $current = Route::current();

        // Exceptional case for HTTP 404 errors (which have no route)
        if (! $current) {
            return false;
        }

        return $current->getActionName() === 'App\Http\Controllers\\'.$action;
    }
}
