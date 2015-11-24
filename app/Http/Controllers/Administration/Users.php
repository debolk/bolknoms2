<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Application;
use App\Models\User;
use Illuminate\Http\Request;

class Users extends Application
{
    /**
     * Show a list of users
     * @return Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('administration/users/index', compact('users'));
    }

    /**
     * Update the handicap of a user
     * @param integer  $id
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function setHandicap($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->handicap = $request->input('handicap');
        $user->save();
        return response(null, 204);
    }
}
