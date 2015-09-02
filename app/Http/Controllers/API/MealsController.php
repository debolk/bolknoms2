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
	public function create()
	{
		//
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
