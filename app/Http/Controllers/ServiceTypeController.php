<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{

	public function index()
	{
		$service_types = ServiceType::selectRaw('service_types.*, COALESCE(i.cnt,0) AS counter')
		->joinSub('SELECT service_type_id, count(service_type_id) cnt FROM clients GROUP BY service_type_id', 'i',
			'i.service_type_id', '=', 'service_types.id', 'LEFT')
		->orderBy('description')
		->paginate(20);

		return view('admin.servicetypes', compact('service_types'));
	}

	public function create()
	{
		return view('admin.servicetype-create');
	}

	public function store(Request $request)
	{
		$request->validate([
			'description' => 'required|max:50|unique:service_types,description'
		]);

		$service_type = new ServiceType;
		$service_type->description = $request->description;
		$service_type->points = $request->points;
		$res = $service_type->save();

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
		$service_type = ServiceType::find($id);

		return view('admin.servicetype-edit', compact('service_type'));
	}

	public function update(Request $request, $id)
	{
		$service_type = ServiceType::find($id);
		$service_type->description = $request->description;
		$service_type->points = $request->points;
		$res = $service_type->save();

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
		$res = ServiceType::find($id);

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