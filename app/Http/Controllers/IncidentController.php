<?php

namespace App\Http\Controllers;

use App\Filters\IncidentFilters;
use App\Models\Area;
use App\Models\IncidentAttachment;
use App\Models\Incident;
use App\Models\Client;
use App\Models\Group;
use App\Models\IncidentState;
use App\Models\Module;
use App\Models\Priority;
use App\Models\ProgressType;
use App\Models\Problem;
use App\Models\Sla;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\WithStoredFilters;
use App\Services\IncidentServices;
use Illuminate\Support\Facades\DB;

class IncidentController extends Controller
{
	use WithStoredFilters;

	private $incident_service;

	public function __construct(IncidentServices $service)
	{
		$this->incident_service = $service;
	}

	public function index()
	{	
		$filters = $this->getStoredFilters('filters');

		$incidents = $this->incident_service->getIncidentList($filters);
		
        $incidents = $incidents->paginate(15);
		
		$users = null;
		$groups = null;
		$clients = null;
		$areas = null;
		$modules = null;
		$problems = null;
		$unassigned = 0;

		if (Auth::user()->type==1)
		{
			//$users = User::actives()->where('type', 1)->pluck('name', 'id')->toArray();
			//$groups = Group::orderBy('description')->get()->pluck('description', 'id')->toArray();
			//$clients = Client::actives()->toArray();
			//$areas = Area::orderBy('description')->get();
			//$modules = Module::orderBy('description')->get();
			//$problems = Problem::orderBy('description')->get();
			$users = DB::table('users')->orderBy('name')->pluck('name', 'id')->toArray();
			$groups = DB::table('groups')->orderBy('description')->pluck('description', 'id')->toArray();
			$clients = DB::table('clients')->where('active', 1)->orderBy('description')->pluck('description', 'id')->toArray();
			$areas = DB::table('areas')->orderBy('description')->pluck('description', 'id')->toArray();
			$modules = DB::table('modules')->orderBy('description')->pluck('description', 'id')->toArray();
			$problems = DB::table('problems')->orderBy('description')->pluck('description', 'id')->toArray();
			$unassigned = $incidents->where('status_id', 0)->count();
		}

		

		//dd($users);
		
		$priorities = Priority::select('id as pid', 'description as pdesc', 'min', 'max')
							->pluck('pdesc', 'pid')->toArray();

		return view('incidents', compact('incidents', 'clients', 'groups', 'users', 'priorities', 
			'problems', 'modules', 'areas', 'filters', 'unassigned'));

		/* return view('incidents'); */
	}

	public function create()
	{
		$groups = Group::orderBy('description')->get()->toArray();
		//$status = IncidentState::whereBetween('id', [0, 10])->get();

		$clients = Auth::user()->type==0 ? 
			Auth::user()->client->toArray() : Client::actives()->toArray();

		return view('incident-create', compact('clients', /* 'status', */ 'groups'));

	}

	public function store(Request $request)
	{
		//dd($request->all());

		$request->validate([
			'title' => 'required',
			'description' => 'required'
		]);

		$incident = [
			'client_id' => Auth::user()->type==1? $request->client_id : Auth::user()->client_id,
			'area_id' => $request->area_id,
			'module_id' => $request->module_id,
			'problem_id' => $request->problem_id,
			'title' => $request->title,
			'description' => $request->description,
			'creator' => Auth::user()->id,
			'group_id' => Auth::user()->type==1? $request->group_id : 0,
			'assigned' => Auth::user()->type==1? $request->assigned : 0,
			'status_id' => Auth::user()->type==1? ($request->assigned!=0? 1 : 0) : 0,
			'created_at' => Auth::user()->type==1? strtotime($request->date) : strtotime(date('Y-m-d H:i'))
		];
		
		$incident['priority'] = $this->incident_service->getPriorityPoints($incident);
		$incident['sla'] = $this->incident_service->getSlaHours($incident);

		if ($incident['group_id']==0)
		{
			list($auto_group, $auto_user, $auto_status) = $this->incident_service->getAssignment($incident);
			$incident['group_id'] = $auto_group;
			$incident['assigned'] = $auto_user;
			$incident['status_id'] = $auto_status;
		}
		


		$res = Incident::create($incident);

		if ($res)
		{
			if ($request->has('archivo'))
			{
				foreach ($request->archivo as $file)
				{
					//list($temp_name, $name) = explode('__', $file);
					$name = $file->getClientOriginalName();
					$path = 'attachments/'.$res->id.'/0';
					//Storage::move($temp_name, $path.'/'.$name);
					//Storage::deleteDirectory(dirname($temp_name));
					$file->storeAs($path, $name);

					IncidentAttachment::create([
						'incident_id' => $res->id,
						'progress_id' => 0,
						'attachment' => $name
					]);
				}
			}

			return to_route('incidents.index')->with('message', __('main.common.saved'));
		}
		else
		{
			return back()->with('error', __('main.common.error_saving'));
		}
	}

	public function edit($id)
	{
		$sla = Sla::first();
		$priorities = Priority::selectRaw('id as pid, description as pdesc, min, max');

		$incident = Incident::with(['client', 'status', 'progress'/* , 'assigned_user', 'assigned_group', 'creator_user' */])
			->selectRaw('incidents.*, pr.pid, pr.pdesc, areas.description as area_desc, 
				modules.description as module_desc, problems.description as problem_desc,
				cr.name as creator_user_name, as.name as assigned_user_name')
			->leftJoinSub($priorities, 'pr', function ($join) {
				$join->on('incidents.priority', '>=', 'pr.min');
				$join->on('incidents.priority', '<=', 'pr.max');
			})
			->leftJoin('areas', 'areas.id', '=', 'area_id')
			->leftJoin('modules', 'modules.id', '=', 'module_id')
			->leftJoin('problems', 'problems.id', '=', 'module_id')
			->leftJoin('users as cr', 'cr.id', '=', 'creator')
			->leftJoin('users as as', 'as.id', '=', 'assigned')
			->findOrFail($id);

		$this->authorize('ver_inc', $incident);

		$groups = DB::table('groups')->orderBy('description')->pluck('description', 'id')->toArray();
		//$status = IncidentState::whereBetween('id', [0, 10])->get();
		//$users = DB::table('users')->orderBy('name')->pluck('name', 'id')->toArray();
		
		$progress_types = $incident->status_id==10?
			ProgressType::whereIn('id', [6, 20])->get() :
			ProgressType::whereNotIn('id', [6, 30, 100])->get();

		if (Auth::user()->type==0)
			$clients = Client::where('id', Auth::user()->client_id)->get()->toArray();

		else
			$clients = Client::actives()->toArray();

		return view('incident-edit', compact('incident', 'clients', /* 'status', */ 'groups', 
			'progress_types', /* 'users',  */'sla'));

		//return view('incident-edit', compact('id'));

	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'title' => 'required',
			'description' => 'required'
		]);

		$incident = Incident::find($id);

		$newrequest = $request->all();
		$newrequest['updated_at'] = strtotime(date('Y-m-d H:i'));
		$newrequest['title'] = $newrequest['title'];
		$newrequest['description'] = $newrequest['description'];
		$newrequest['creator'] = $incident->creator;
		$newrequest['priority'] = $this->incident_service->getPriorityPoints($newrequest);
		$newrequest['sla'] = $this->incident_service->getSlaHours($newrequest);

		if ($incident->update($newrequest))
		{
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}

	}


}