<?php

namespace App\Http\Controllers\API;

use App\Models\Meal;
use App\Services\DoubleRegistrationException;
use App\Services\MealCapacityExceededException;
use App\Services\MealDeadlinePassedException;
use App\Services\RegisterService;
use App\Services\UserBlockedException;
use App\Services\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class RegistrationsController extends APIController
{
    public function store(string $mealUUID)
    {
        $meal = Meal::whereUUID($mealUUID)->firstOrFail();

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
}
