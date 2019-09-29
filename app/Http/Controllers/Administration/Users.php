<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Users extends Application
{
    /**
     * Show a list of users
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('administration/users/index', compact('users'));
    }

    /**
     * Update the handicap of a user
     * @param integer  $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function setHandicap($id, Request $request)
    {
        $user = User::findOrFail($id);
        $existingHandicap = $user->handicap;
        $user->handicap = $request->input('handicap');
        $user->save();
        Log::info('User changed diet', [
            'user' => $user->id,
            'handicap' => $user->handicap,
            'was' => $existingHandicap,
            'changed_by' => $this->oauth->user()->id,
        ]);
        return view('administration/users/_user', compact('user'));
    }

    /**
     * Block a user from the system
     * @param  integer $id
     * @return \Illuminate\View\View
     */
    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = true;
        $user->save();
        return view('administration/users/_user', compact('user'));
    }

    /**
     * Unblock a user from the system
     * @param  integer $id
     * @return \Illuminate\View\View
     */
    public function release($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = false;
        $user->save();
        return view('administration/users/_user', compact('user'));
    }
}
