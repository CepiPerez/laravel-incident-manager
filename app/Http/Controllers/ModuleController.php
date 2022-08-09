<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
	public function index()
	{
		$modules = Module::selectRaw('modules.*, COALESCE(i.cnt,0) AS counter')
		->joinSub('SELECT module_id, count(module_id) cnt FROM incidents GROUP BY module_id', 'i',
			'i.module_id', '=', 'modules.id', 'LEFT')
		->orderBy('description')
		->paginate(20);

		return view('admin.modules', compact('modules'));
	}

	public function create()
	{
		$problems = Problem::orderBy('description')->get();

		return view('admin.module-create', compact('problems'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'description' => 'required|max:50|unique:modules,description'
		]);

		$modulo = new Module;
		$modulo->description = $request->description;
		$modulo->points = $request->priority;
		$modulo->active = 1;
		$res = $modulo->save();

		if ($res)
		{
			if ($request->modules)
			{
				$modules = array(); 
				foreach ($request->modules as $key => $val)
					$modules[] = $val;
				
				$modulo->problems()->sync($modules);
			}
			else
			{
				$modulo->problems()->sync([]);
			}

			return back()->with('message', __('main.common.saved'));
		}
		else
		{
			return back()->with('error', __('main.common.error_saving'));
		}

	}

	public function edit($id)
	{
		$module = Module::find($id);
		$problems = Problem::orderBy('description')->get();

		return view('admin.module-edit', compact('module', 'problems'));
	}

	public function update(Request $request, $id)
	{
		$module = Module::find($id);
		$module->description = $request->description;
		$module->points = $request->priority;
		$module->active = $request->active;
		$res = $module->save();

		if ($res)
		{
			if ($request->modules)
			{
				$modules = array(); 
				foreach ($request->modules as $key => $val)
					$modules[] = $val;
				
				$module->problems()->sync($modules);
			}
			else
			{
				$module->problems()->sync([]);
			}

			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}
	}

	public function destroy($id)
	{
		$res = Module::find($id);

		if ($res->delete())
		{
			$res->problems()->sync([]);

			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}

}