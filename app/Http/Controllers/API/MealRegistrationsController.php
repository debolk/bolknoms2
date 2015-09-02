<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\Registration;

class MealRegistrationsController extends ApiController {

	/**
	 * Display the registrations of a meal
     * @param int $meal_id
	 * @return Response
	 */
	public function index($meal_id)
	{
        // Find the meal
		$meal = Meal::withTrashed()->find($meal_id);
        if (!$meal) {
            return $this->fatalError(404, 'meal_not_found', 'This meal does not exist');
        }
        if ($meal->deleted_at !== null) {
            return $this->fatalError(410, 'meal_deleted', 'This meal has been removed');
        }

        return response()->json($meal->registrations()->get());
	}

	/**
	 * Create a registration for a meal
	 * @param  int  $meal_id
	 * @return Response
	 */
	public function create($meal_id)
	{
		//
	}
}
