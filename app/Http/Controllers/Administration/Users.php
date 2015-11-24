<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;
use App\Models\User;

class Users extends Application
{
    /**
     * List all past and current meals
     * @return View
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('administration/users/index', compact('users'));
    }
}
