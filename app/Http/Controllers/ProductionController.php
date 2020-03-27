<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductionController extends Controller 
{
	/**
	 * Return the filtered productions in JSON.
	 *
	 * @param Request $request
	 * @return void
	 */
	public function productions(Request $request) 
	{
		$validator = Validator::make($request->all(), [
			'from' => 'required|date_format:Y-m-d',
			'to' => 'required|date_format:Y-m-d',
		]);
		 
		if ($validator->fails()) {
			return response()->json(
				[
					'errors' => $validator->errors()
				], 
				422
			);
		} 

		$service = resolve('production_service');
		return $service->getProductions($request->input('from'), $request->input('to'));
	}

	/**
	 * Render the filtered productions to the view.
	 *
	 * @param Request $request
	 * @return void
	 */
	public function showProductions(Request $request) 
	{
		$validator = Validator::make($request->all(), [
			'from' => 'required|date_format:Y-m-d',
			'to' => 'required|date_format:Y-m-d',
		]);
		 
		if ($validator->fails()) {
			return view('error', [
				'errors' => $validator->errors()
			]);
		} else {
			$service = resolve('production_service');
			$data = $service->getProductions($request->input('from'), $request->input('to'));
		}
		
		return view('show', [
			'data' => $data
		]);
	}
}
