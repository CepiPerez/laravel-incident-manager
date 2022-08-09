<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Client;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ClientController extends Controller
{

	public function index()
	{
		$clients = Client::selectRaw('clients.*, service_types.description as service, COALESCE(i.cnt,0) AS counter')
		->joinSub('SELECT client_id, count(client_id) cnt FROM incidents GROUP BY client_id', 'i',
			'i.client_id', '=', 'clients.id', 'LEFT')
		->leftJoin('service_types', 'service_types.id', '=', 'service_type_id')
		->orderBy('description')
		->paginate(20);

		return view('admin.clients', compact('clients'));
	}

	public function create()
	{
		$areas = Area::get();
		$service_types = ServiceType::get();

		return view('admin.client-create', compact('areas', 'service_types'));
	}

	public function store(Request $request)
	{
		
		$request->validate([
			'description' => 'required|max:100|unique:clients,description'
		]);

		$cl = array();
		$cl['description'] = $request->description;
		$cl['service_type_id'] = $request->service_type_id;
		$cl['active'] = 1;
		
		$res = Client::create($cl);

		if ($request->areas)
		{
			$areas = array(); 
			foreach ($request->areas as $key => $val)
				$areas[] = $val;
			
			$res->areas()->sync($areas);
		}

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
		$client = Client::find($id);
		$areas = Area::get();
		$areas_client = $client->areas->pluck('id')->toArray();
		$service_types = ServiceType::get();

		return view('admin.client-edit', compact('client', 'areas', 'areas_client', 'service_types'));
	}

	public function update(Request $request, $id)
	{
		$cli = Client::find($id);

		if ($request->areas)
		{
			$areas = array(); 
			foreach ($request->areas as $key => $val)
				$areas[] = $val;
			
			$cli->areas()->sync($areas);
		}

		$cli->service_type_id = $request->service_type_id;
		$cli->description = $request->description;
		$cli->active = $request->active;
		$res = $cli->save();

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
		$res = Client::find($id);

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