<?php

namespace App\Http\Controllers;

use App\Exports\IncidentsExport;
use App\Models\Client;
use App\Models\Group;
use App\Models\Incident;
use App\Models\IncidentState;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ReportController extends Controller
{
	
    public function index()
	{
		$this->authorize('informes');

		$clients = Client::actives()->pluck('description', 'id');
		$states = IncidentState::orderBy('description')->get()->pluck('description', 'id');
		$users = User::actives()->where('type', 1)->pluck('name', 'id');
		$groups = Group::orderBy('description')->pluck('description', 'id');

		return view('reports', compact('clients', 'states', 'users', 'groups'));
	}

	public function process(Request $request)
	{
		$this->authorize('informes');

		$start = date('Y-m-d H:i', strtotime($request->date_from));
		$end = date('Y-m-d H:i', strtotime('+1 day', strtotime($request->date_to)));
		
		$filters = array();
		$filters['date_from'] = $request->date_from;
		$filters['date_to'] = $request->date_to;

		if (Auth::user()->type==0)
		{
			$request->assigned = 'all';
			$request->client = Auth::user()->client->id;
		}

		$incidents = Incident::selectRaw('incidents.id, incidents.created_at,
			incidents.status_id, incidents.client_id, incidents.title,
			clients.description as client_desc, incident_states.description as status_desc')
			->leftJoin('incident_states', 'incident_states.id', '=', 'status_id')
			->leftJoin('clients', 'clients.id', '=', 'client_id')
			->whereBetween('created_at', [$start, $end]);

		if ($request->group_id != 'all')
		{
			$incidents = $incidents->where('group_id', $request->group_id);
			$filters['group_id'] = Group::where('id', $request->group_id)->first()->description;
		}

		if ($request->assigned != 'all')
		{
			$incidents = $incidents->where('assigned', $request->assigned);
			$filters['assigned'] = User::where('id', $request->assigned)->first()->name;
		}

		if ($request->client != 'all')
		{
			$incidents = $incidents->where('client_id', $request->client);
			$filters['client'] = $request->client;
			$filters['client_desc'] = Client::find($request->client)->description;
		}

		if ($request->status != 'all' && $request->status != 'allinc')
		{
			$incidents = $incidents->where('status_id', $request->status);
			$filters['status'] = $request->status; 
			$filters['status_desc'] = __('main.status.'.IncidentState::find($request->status)->description); 
		}
		else
		{
			if ($request->status == 'all')
			{
				$incidents = $incidents->where('status_id', '<', 50);
				$filters['status_desc'] = __('main.reports.exclude_cancel'); 
			}
			else
			{
				$filters['status_desc'] = __('main.reports.include_cancel'); 
			}
			$filters['status'] = $request->status;
		}

        session()->put('report_filters', $filters);
		session()->reflash();

		$incidents = $incidents->paginate(20);

		return view('reports-result', compact('incidents', 'filters'));

	}

	public function download()
	{
		$this->authorize('informes');

        $filters = session('report_filters', []);

		$start = date('Y-m-d H:i', strtotime($filters['date_from']));
		$end = date('Y-m-d H:i', strtotime('+1 day', strtotime($filters['date_to'])));
		
		$incidents = Incident::with('progress_short')
            ->selectRaw('incidents.*, clients.description as client_desc,
                areas.description as area_desc, modules.description as module_desc,
                problems.description as problem_desc, users.name as assigned_desc,
                incident_states.description as status_desc')
            ->leftJoin('users', 'users.id', '=', 'incidents.assigned')
            ->leftJoin('clients', 'clients.id', '=', 'incidents.client_id')
            ->leftJoin('areas', 'areas.id', '=', 'incidents.area_id')
            ->leftJoin('modules', 'modules.id', '=', 'incidents.module_id')
            ->leftJoin('incident_states', 'incident_states.id', '=', 'incidents.status_id')
            ->leftJoin('problems', 'problems.id', '=', 'incidents.problem_id')
            ->whereBetween('incidents.created_at', [$start, $end])
            ->orderBy('id');

        if (!isset($filters['assigned'])) $filters['assigned'] = 'all';
        if (!isset($filters['client'])) $filters['client'] = 'all';
        if (!isset($filters['status'])) $filters['status'] = 'all';


		if ($filters['assigned'] != 'all')
		{
			$incidents = $incidents->where('assigned', $filters['assigned']);
		}

		if ($filters['client'] != 'all')
		{
			$incidents = $incidents->where('client_id', $filters['client']);
		}

		if ($filters['status'] != 'all' && $filters['status'] != 'allinc')
		{
			$incidents = $incidents->where('status_id', $filters['status']);
		}
		else
		{
			if ($filters['status'] == 'all')
			{
				$incidents = $incidents->where('status_id', '<', 50);
			}
		}

        $incidents = $incidents->get();


		foreach ($incidents as $inc)
		{
			foreach($inc->progress_short as $avance)
			{
				if ($avance->progress_type_id == 10)
				{
					# Adding resolved time
					$inc->resolution = Date::dateTimeToExcel($avance->created_at);

					# Get opened total time
					$inc->time = round((strtotime($inc->resolution) - strtotime($inc->created_at))/3600, 2);
				}
				elseif ($avance->progress_type_id == 20)
				{
					# Adding closed time
					$inc->close = $avance->created_at;

					# Get opened total time
					# If already calculated when resolved then skip
					if (!isset($inc->time))
					{
						$inc->time = round((strtotime($inc->close) - strtotime($inc->created_at))/3600, 2);
					}
				}
			}

            if (!isset($inc->time)) $inc->time = 0;

            $inc->open = Date::dateTimeToExcel($inc->created_at);

            $inc->sla_desc = $inc->sla==0? 'Incidente sin SLA' :
                ($inc->time > $inc->sla? 'Fuera del SLA' : 'Dentro del SLA');
		}


        //return view('exports.incidents', compact('incidents'));

        return Excel::download(new IncidentsExport($incidents->toArray()), 'report.xlsx');


	}

}
