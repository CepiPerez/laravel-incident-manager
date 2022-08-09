<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Module;
use Illuminate\Http\Request;


class AreaController extends Controller
{
	
	public function index()
	{

		$areas = Area::selectRaw('areas.*, COALESCE(i.cnt, 0) AS counter')
		->joinSub('SELECT area_id, count(area_id) cnt FROM incidents GROUP BY area_id', 'i',
			'i.area_id', '=', 'areas.id', 'LEFT')
		->orderBy('description')
		->paginate(20);

		return view('admin.areas', compact('areas'));
	}

	public function create()
	{
		$modules = Module::orderBy('description')->get();

		return view('admin.area-create', compact('modules'));
	}

	public function store(Request $request)
	{

		$request->validate([
			'description' => 'required|max:50|unique:areas,description'
		]);

		$area = new Area;
		$area->description = $request->description;
		$area->points = $request->priority;

		if ($request->modules)
		{
			$modules = array(); 
			foreach ($request->modules as $key => $val)
				$modules[] = $val;
			
			$area->modules()->sync($modules);
		}
		else
		{
			$area->modules()->sync([]);
		}

		if ($area->save())
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
		$area = Area::find($id);
		$modules = Module::orderBy('description')->get();

		return view('admin.area-edit', compact('area', 'modules'));
	}

	public function update(Request $request, $id)
	{
		$area = Area::find($id);
		$area->description = $request->description;
		$area->points = $request->priority;

		if ($request->modules)
		{
			$modules = array(); 
			foreach ($request->modules as $key => $val)
				$modules[] = $val;
			
			$area->modules()->sync($modules);
		}
		else
		{
			$area->modules()->sync([]);
		}
		
		if ($area->save())
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
		$area = Area::find($id);
		
		if ($area->delete())
		{
			$area->modules()->sync([]);
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}


}