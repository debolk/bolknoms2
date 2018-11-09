<?php

namespace App\Http\Controllers\Administration;

use App\Models\Vacation;

class Vacations
{
    public function index()
    {
        return view('administration/vacations/index', [
            'vacations' => Vacation::orderBy('start', 'desc')->get(),
        ]);
    }

    public function destroy(Vacation $vacation)
    {
        $vacation->delete();

        return back()->with('action_result', [
            'status' => 'success',
            'message' => 'Vakantie verwijderd',
        ]);
    }
}
