<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Registration;
use App\Services\DeregisterService;
use App\Services\DoubleRegistrationException;
use App\Services\MealCapacityExceededException;
use App\Services\MealDeadlinePassedException;
use App\Services\RegisterService;
use App\Services\UserBlockedException;
use App\Services\ValidationException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Register extends Controller
{
    /**
     * Show the index that allows users to quickly register for the upcoming meal
     */
    public function index(): View
    {
        $data = [];

        // Add more data if we have a current user
        if ($this->oauth->valid()) {
            // A registered user can subscribe to any meal
            $data['meals'] = Meal::upcoming()->get();
            $data['user'] = $this->oauth->user();
        } else {
            // An anonymous user can subscribe to the next available meal
            $meals = Meal::available()->take(1)->get();
            // or any meal that is available with a description (aka `special` meals)
            $meals = $meals->merge(Meal::available()->whereNotNull('event')->get());
            $data['meals'] = $meals;
        }

        return view('register/index', $data);
    }

    /**
     * Register a user for a meal
     * @param  Request $request
     */
    public function aanmelden(Request $request): JsonResponse
    {
        $data = $request->all();

        // Populate the data from the session if not passed
        if (! $request->has('name')) {
            $user = $this->oauth->user();
            if (! $user) {
                return $this->ajaxError(
                    500,
                    'user_not_found',
                    'Je gebruikersaccount kon niet worden gevonden. Probeer opnieuw in te loggen.'
                );
            }
            $data['user_id'] = $user->id;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['handicap'] = $user->handicap;
        }

        // Create registration
        try {
            $registration = with(new RegisterService($data, $this->oauth->user()))->execute();

            return response()->json([], 204);
        } catch (ModelNotFoundException $e) {
            return $this->ajaxError(404, 'meal_not_found', 'De maaltijd waarvoor je je probeert aan te melden bestaat niet');
        } catch (ValidationException $e) {
            return $this->ajaxError(400, 'input_invalid', 'Naam of e-mailadres niet ingevuld of ongeldig');
        } catch (MealDeadlinePassedException $e) {
            return $this->ajaxError(400, 'meal_deadline_expired', 'De aanmeldingsdeadline is verstreken');
        } catch (UserBlockedException $e) {
            return $this->ajaxError(404, 'user_blocked', 'Je bent geblokkeerd op bolknoms. Je kunt je niet aanmelden voor maaltijden.');
        } catch (DoubleRegistrationException $e) {
            return $this->ajaxError(400, 'double_registration', 'Je bent al aangemeld voor deze maaltijd.');
        } catch (MealCapacityExceededException $e) {
            return $this->ajaxError(400, 'capacity_exceeded', 'De limiet voor het aantal eters is bereikt.');
        }
    }

    /**
     * Unsubscribe a user from a meal
     */
    public function afmelden(Request $request): JsonResponse
    {
        // Find the meal
        $meal = Meal::where('id', (int) $request->input('meal_id'))->first();
        if (! $meal) {
            return $this->ajaxError(404, 'meal_not_found', 'De maaltijd bestaat niet');
        }

        // Find the user
        $user = $this->oauth->user();
        if (! $user) {
            return $this->ajaxError(404, 'no_user', 'Deze gebruikers bestaat niet');
        }

        $registration = $user->registrationFor($meal);
        if (! $registration) {
            return $this->ajaxError(404, 'no_registration', 'Je bent niet aangemeld voor deze maaltijd');
        }

        // Deregister from the meal
        try {
            with(new DeregisterService($registration))->execute();
        } catch (MealDeadlinePassedException $e) {
            return $this->ajaxError(400, 'meal_deadline_expired', 'De aanmeldingsdeadline is verstreken');
        }

        return response()->json([], 204);
    }
}
