<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Meal;

class MealsController extends ApiController
{

	/**
	 * Display a list of Meals
	 * @return Response
	 */
	public function index(Request $request)
	{
        // Validate input parameters
        $validator = \Validator::make($request->all(), [
            'from' => ['regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', 'date'],
            'to' => ['regex:/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', 'date']
        ],[
            'from.regex' => 'from parameter must be in the format YYYY-MM-DD',
            'from.date' => 'from parameter must be in a valid date',
            'to.regex' => 'to parameter must be in the format YYYY-MM-DD',
            'to.date' => 'to parameter must be in a valid date'
        ]);
        if (!$validator->passes()) {
            return $this->validationErrors($validator->messages());
        }

        $meals = Meal::query();

        // Limit by dates if requested
        $from = $request->input('from', null);
        if ($from) {
            $meals = $meals->where('date', '>=', $from);
        }
        $to = $request->input('to', null);
        if ($to) {
            $meals = $meals->where('date', '<=', $to);
        }

        // Return JSON-encoded meals
		return response()->json($meals->get());
	}

	/**
	 * Create a new Meal
	 * @return Response
	 */
	public function create(Request $request)
	{
        // Validate the input
        $validator = \Validator::make($request->all(), [
            'date'        => ['date', 'required', 'unique:meals', 'after:yesterday'],
            'locked_date' => ['date', 'required', 'after:yesterday'],
            'locked'      => ['regex:/^[0-2][0-9]:[0-5][0-9]$/'],
            'mealtime'    => ['regex:/^[0-2][0-9]:[0-5][0-9]$/'],
        ],[
            'date.required'        => 'date is required',
            'date.date'            => 'date is not a valid date',
            'date.unique'          => 'date is not unique',
            'date.after'           => 'date cannot be in the past',
            'locked_date.required' => 'locked_date is required',
            'locked_date.date'     => 'locked_date is not a valid date',
            'locked_date.after'    => 'locked_date cannot be in the past',
            'locked.regex'         => 'locked must be formatted as HH:MM',
            'mealtime.regex'       => 'mealtime must be formatted as HH:MM',
        ]
        );

        if (! $validator->passes()) {
            return $this->validationErrors($validator->messages());
        }

        // Save new meal
        $meal = new Meal($request->all());
        if ($meal->save()) {
            return response()->json($meal);
        }
        else {
            return $this->fatalError(500, 'unknown_error', 'Unknown error while saving a meal');
        }
	}

	/**
	 * Display a meal
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        // Find the meal
        $meal = Meal::withTrashed()->find($id);
        if (!$meal) {
            return $this->fatalError(404, 'meal_not_found', 'This meal does not exist');
        }
        if ($meal->deleted_at !== null) {
            return $this->fatalError(410, 'meal_deleted', 'This meal has been removed');
        }

        return response()->json($meal);
	}

	/**
	 * Update a meal
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Destroy a meal
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		//
	}
}
