<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Illuminate\Http\Request;

class DocsController extends ApiController
{
	public function index()
	{
        return view('api/docs/index');
	}
}
