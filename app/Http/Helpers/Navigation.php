<?php

namespace App\Http\Helpers;

use App\Http\Controllers\Administration\Dashboard;
use App\Http\Controllers\Administration\Meals;
use App\Http\Controllers\Administration\Users;
use App\Http\Controllers\Administration\Vacations;
use App\Http\Controllers\Page;
use App\Http\Controllers\Profile;
use App\Http\Controllers\Register;
use App\Http\Controllers\Top;
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
        ['text' => 'Aanmelden', 'action' => [Register::class, 'index'], 'icon' => 'calendar', 'level' => '0'],
        ['text' => 'Spelregels', 'action' => [Page::class, 'spelregels'], 'icon' => 'file-text-o', 'level' => '0'],
        ['text' => 'Top eters', 'action' => [Top::class, 'index'], 'icon' => 'trophy', 'level' => '1'],
        ['text' => 'Mijn profiel', 'action' => [Profile::class, 'index'], 'icon' => 'user', 'level' => '1'],
        ['text' => 'Administratie', 'action' => [Dashboard::class, 'index'], 'icon' => 'wrench', 'level' => '2', 'submenu' => [
            ['text' => 'Maaltijden', 'action' => [Meals::class, 'index'], 'icon' => 'cutlery'],
            ['text' => 'Gebruikers', 'action' => [Users::class, 'index'], 'icon' => 'users'],
            ['text' => 'Vakanties', 'action' => [Vacations::class, 'index'], 'icon' => 'plane'],
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
    private function isCurrent(array $action): bool
    {
        $current = Route::current();

        // Exceptional case for HTTP 404 errors (which have no route)
        if (! $current) {
            return false;
        }

        return $current->getActionName() === $action[0] . '@' . $action[1];
    }
}
