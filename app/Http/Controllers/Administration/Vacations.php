<?php

namespace App\Http\Controllers\Administration;

use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Vacations
{
    public function index(): View
    {
        return view('administration/vacations/index', [
            'vacations' => Vacation::orderByDesc('start')->get(),
            'currentVacation' => Vacation::contains(Carbon::now())->first(),
            'upcomingVacation' => Vacation::upcoming()->first(),
        ]);
    }

    public function destroy(Vacation $vacation): RedirectResponse
    {
        $vacation->delete();

        return back()->with('action_result', [
            'status' => 'success',
            'message' => 'Vakantie verwijderd',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $vacation = Vacation::create($request->only('start', 'end'));

        return back()->with('action_result', [
            'status' => 'success',
            'message' => 'Vakantie toegevoegd '.$vacation->span(),
        ]);
    }
}
