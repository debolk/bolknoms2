<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Registration;
use App\Services\DeregisterService;
use App\Services\DoubleRegistrationException;
use App\Services\MealCapacityExceededException;
use App\Services\MealDeadlinePassedException;
use App\Services\RegisterService;
use App\Services\UserBlockedException;
use App\Services\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RegistrationsController extends Controller
{
    use SendsAPIErrors;

    public function store(string $mealUUID): Response|JsonResponse
    {
        $meal = Meal::whereUUID($mealUUID)->first();

        $user = Auth::user();
        $data = [
            'meal_id' => $meal->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'handicap' => $user->handicap,
        ];

        // Create registration
        try {
            (new RegisterService($data, Auth::user()))->execute();
            return response()->noContent();
        } catch (ModelNotFoundException) {
            return $this->errorResponse(404, 'meal_not_found', 'De maaltijd waarvoor je je probeert aan te melden bestaat niet');
        } catch (ValidationException) {
            return $this->errorResponse(400, 'input_invalid', 'Naam of e-mailadres niet ingevuld of ongeldig');
        } catch (MealDeadlinePassedException) {
            return $this->errorResponse(400, 'meal_deadline_expired', 'De aanmeldingsdeadline is verstreken');
        } catch (UserBlockedException) {
            return $this->errorResponse(400, 'user_blocked', 'Je bent geblokkeerd op bolknoms. Je kunt je niet aanmelden voor maaltijden.');
        } catch (DoubleRegistrationException) {
            return $this->errorResponse(400, 'double_registration', 'Je bent al aangemeld voor deze maaltijd.');
        } catch (MealCapacityExceededException) {
            return $this->errorResponse(400, 'capacity_exceeded', 'De limiet voor het aantal eters is bereikt.');
        }
    }

    public function destroy(string $mealUUID, string $registrationUUID): Response|JsonResponse
    {
        $registration = Registration::whereUUID($registrationUUID)->first();

        if (!$registration->user->is(Auth::user())) {
            return $this->errorResponse(403, 'object_not_owned', 'Deze aanmelding is niet van jou');
        }

        try {
            (new DeregisterService($registration))->execute();
        } catch (MealDeadlinePassedException) {
            return $this->errorResponse(400, 'meal_deadline_expired', 'De aanmeldingsdeadline is verstreken');
        }

        return response()->noContent();
    }
}
