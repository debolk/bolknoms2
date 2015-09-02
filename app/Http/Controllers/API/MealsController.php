<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Meal;

class MealsController extends ApiController
{

	/**
	 * Display a listing of the resource.
	 *
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
            return $this->fatalError(400, 'parameters_unacceptable', $validator->messages());
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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
