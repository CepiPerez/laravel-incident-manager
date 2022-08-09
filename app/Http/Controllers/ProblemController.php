<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
	public function index()
	{

		$problems = Problem::selectRaw('problems.*, COALESCE(i.cnt,0) AS counter')
		->joinSub('SELECT problem_id, count(problem_id) cnt FROM incidents GROUP BY problem_id', 'i',
			'i.problem_id', '=', 'problems.id', 'LEFT')
		->orderBy('description')
		->paginate(20);

		return view('admin.problems', compact('problems'));
	}

	public function create()
	{
		return view('admin.problems-create');
	}

	public function store(Request $request)
	{
		$request->validate([
			'description' => 'required|max:50|unique:problems,description'
		]);

		$problem = new Problem;
		$problem->description = $request->description;
		$problem->points = $request->points;
		$problem->active = 1;
		$res = $problem->save();
		
		if ($res)
		{
			return back()->with('message', __('main.common.saved'));
		}
		else
		{
			return back()->with('error', __('main.common.error_saving'));
		}

	}

	public function edit($id)
	{
		$problem = Problem::find($id);
		return view('admin.problems-edit', compact('problem'));
	}

	public function update(Request $request, $id)
	{
		$problem = Problem::find($id);
		$problem->description = $request->description;
		$problem->points = $request->points;
		$problem->active = $request->active;
		$res = $problem->save();

		if ($res)
		{
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}
	}

	public function destroy($id)
	{
		$res = Problem::find($id);

		if ($res->delete())
		{
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}

}