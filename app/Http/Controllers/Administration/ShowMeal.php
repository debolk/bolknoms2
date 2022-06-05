<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use App\Services\AdminDeregisterService;
use App\Services\AdminRegisterService;
use App\Services\DoubleRegistrationException;
use App\Services\UserBlockedException;
use App\Services\ValidationException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ShowMeal extends Controller
{
    /**
     * Shows the details page of a meal
     */
    public function show(int $id): View
    {
        $meal = Meal::find($id);
        if (! $meal) {
            abort(404, 'Maaltijd niet gevonden');
        }

        return view('administration/meal/show', ['meal' => $meal, 'users' => User::orderBy('name')->get()]);
    }

    /**
     * Creates a registration
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function aanmelden(Request $request)
    {
        $data = $request->all();

        // Populate request from data
        if ($request->has('user_id')) {
            $user = User::where('id', $request->user_id)->first();
            if (! $user) {
                return response()->json(['error' => 'user_not_found', 'error_details' => 'Gebruiker bestaat niet'], 400);
            }
            $data['user_id'] = $user->id;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['handicap'] = $user->handicap;
        }

        try {
            // Create registration
            $registration = with(new AdminRegisterService($data, $this->oauth->user()))->execute();

            // Return view of the new registration
            return view('administration/meal/_registration', ['registration' => $registration]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'meal_not_found',
                'error_details' => 'Maaltijd bestaat niet',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'input_invalid',
                'error_details' => $e->messages(),
            ], 400);
        } catch (UserBlockedException $e) {
            return response()->json([
                'error' => 'user_blocked',
                'error_details' => 'Deze gebruiker is geblokkeerd. Je kunt hem of haar niet aanmelden voor maaltijden.',
            ], 403);
        } catch (DoubleRegistrationException $e) {
            return response()->json([
                'error' => 'double_registration',
                'error_details' => 'Deze gebruiker is al aangemeld voor deze maaltijd',
            ], 400);
        }
    }

    /**
     * Removes a registration from a meal
     * @param int $id the id of the registration to remove
     * @return string "success" if succesfull
     */
    public function afmelden(int $id)
    {
        // Find registration
        $registration = Registration::find($id);
        if (! $registration) {
            return response()->json([
                'error' => 'registration_not_existent',
                'error_details' => 'Deze registratie bestaat niet',
            ], 500);
        }

        // Deregister from the meal
        with(new AdminDeregisterService($registration))->execute();

        return response()->noContent();
    }
}
