<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\PriorityRule;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
	public function index()
	{
		$this->authorize('isadmin');

		$priorities = Priority::get();
		$rules = PriorityRule::orderBy('description')->get();

		return view('admin.priorities', compact('priorities', 'rules'));
	}

	public function edit($id)
	{
		$this->authorize('isadmin');

		$priority = Priority::find($id);
		
		return view('admin.priorities-edit', compact('priority'));
	}

	public function update(Request $request, $id)
	{
		$this->authorize('isadmin');

		$priority = Priority::find($id);
		$priority->description = $request->description;
		$priority->min = (int)$request->min;
		$priority->max = (int)$request->max;

		$res = $priority->save();

		if ($res)
		{
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}
	}

}