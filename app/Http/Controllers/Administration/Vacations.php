<?php

namespace App\Http\Controllers\Administration;

use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Vacations
{
    public function index()
    {
        return view('administration/vacations/index', [
            'vacations' => Vacation::orderBy('start', 'desc')->get(),
            'currentVacation' => Vacation::contains(Carbon::now())->first(),
            'upcomingVacation' => Vacation::upcoming()->first(),
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

    public function store(Request $request)
    {
        $vacation = Vacation::create($request->only('start', 'end'));

        return back()->with('action_result', [
            'status' => 'success',
            'message' => 'Vakantie toegevoegd ' . $vacation->span(),
        ]);
    }
}
