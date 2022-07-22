<?php

namespace App\Http\Controllers;

use App\Filters\IncidentFilters;
use App\Models\Area;
use App\Models\Assignation;
use App\Models\IncidentAttachment;
use App\Models\Incident;
use App\Models\Client;
use App\Models\Group;
use App\Models\IncidentState;
use App\Models\Module;
use App\Models\Priority;
use App\Models\PriorityRule;
use App\Models\ProgressType;
use App\Models\Problem;
use App\Models\Sla;
use App\Models\SlaRule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\WithMixedFilters;

class IncidentsController extends Controller
{

	use WithMixedFilters;

	/* protected $incident_filters;

	public function __construct(IncidentFilters $incident_filters)
	{
		$this->incident_filters = $incident_filters;
	} */

	private function getPriorityPoints(array $inc)
	{
		$points = 0; 

		$area = Area::find($inc['area_id'])->points;
		$modulo = Module::find($inc['module_id'])->points;
		$tipoinc = Problem::find($inc['problem_id'])->points;
		$tiposerv = Client::selectRaw('clients.id, clients.service_type_id, service_types.points')
			->leftJoin('service_types', 'service_types.id', '=', 'service_type_id')
			->where('clients.id', $inc['client_id'])->first()->points;

		$points = $area + $modulo + $tipoinc + $tiposerv;

		$rules = PriorityRule::with('conditions')->where('active', 1)->get();

		foreach ($rules as $rule)
		{
			$points += PrioritiesController::getRulePoints($rule, $inc);
		}

		return $points;
	}

	private function getSlaHours(array $inc)
	{
		$rules = SlaRule::with('conditions')->where('active', 1)->get();

		$sla = Sla::first()->sla_default;

		foreach ($rules as $rule)
		{
			$new_sla = SlaController::getRuleValue($rule, $inc);

			if ($new_sla)
			{
				$sla = $new_sla;
				break;
			}
		}

		return $sla;
	}

	private function getAssignment(array $inc)
	{
		$rules = Assignation::with('conditions')->where('active', 1)->get();

		foreach ($rules as $rule)
		{
			$new_assignemt = AssignationsController::getRuleValue($rule, $inc);

			if ($new_assignemt)
				return $new_assignemt;
		}

		return [0, 0, 0];
	}

	/* private function getFilters()
	{
		$filter_values = [
			'assigned', 'client_id', 'group_id', 'status_id', 'problem_id', 
			'module_id', 'area_id', 'pid', 'order', 'search'
		];

		$filters = [];

		$stored = session('filters', []);

		foreach ($filter_values as $filter)
		{
			$filters[$filter] = $this->checkFilter($filter, $stored[$filter] ?? null);
		}

		session()->put('filters', $filters);
		session()->reflash();

		return $filters;

	} */

	/* private function checkFilter($filter, $default)
	{
		if (request()->has($filter))
		{
			if (request()->$filter!='all')
				return request()->$filter;

			if (request()->has($filter) && (request()->$filter=='all' || request()->$filter==null))
				return null;
		}

		return $default;
	} */
	
	/* private function applyFilters($data, $filters)
	{
		if (isset($filters['search']) && $filters['search']!='')
		{
			$data = $data->search($filters['search']);
		}

		foreach (['assigned', 'client_id', 'group_id', 'problem_id', 'module_id', 'area_id', 'pid'] as $filter)
		{
			if (isset($filters[$filter]))
				$data = $data->where($filter, $filters[$filter]);

		}

		if (isset($filters['status_id']))
		{
			if ($filters['status_id']=='opened')
				$data = $data->where('status_id', '<', 10);

			elseif ($filters['status_id']=='ended')
				$data = $data->whereIn('status_id', [10, 20]);

			else
				$data = $data->where('status_id', $filters['status_id']);
		}

		if ($filters['order'])
		{
			list($order, $sort) = explode('__', $filters['order']);
			$data = $data->orderBy($order, $sort);
		}
		else
		{
			$data = $data->orderBy('id', 'asc');
		}

		$data = $data->filterByUser(Auth::user());

		return $data;
	} */


	public function index(IncidentFilters $incident_filters)
	{	
		$filters = $this->getMixedFilters('filters');

		$data = Incident::getIncidentList();

		$data = $data->filter($incident_filters, array_merge($filters, ['user' => Auth::user()]));
		
        $data = $data->paginate(15);
		
		$users = User::actives()->where('type', 1)->pluck('name', 'id')->toArray();

		$groups = null;
		$clients = null;
		$areas = null;
		$modules = null;
		$problems = null;
		$unassigned = 0;

		if (Auth::user()->type==1)
		{
			$groups = Group::orderBy('description')->get()->pluck('description', 'id')->toArray();
			$clients = Client::actives()->toArray();
			$areas = Area::orderBy('description')->get();
			$modules = Module::orderBy('description')->get();
			$problems = Problem::orderBy('description')->get();
			$unassigned = $data->where('status_id', 0)->count();
		}
		
		$priorities = Priority::select('id as pid', 'description as pdesc', 'min', 'max')
							->pluck('pdesc', 'pid')->toArray();

		return view('incidents', compact('data', 'clients', 'groups', 'users', 'priorities', 
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
		//dd($request);

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
		
		$incident['priority'] = $this->getPriorityPoints($incident);
		$incident['sla'] = $this->getSlaHours($incident);

		if ($incident['group_id']==0)
		{
			list($auto_group, $auto_user, $auto_status) = $this->getAssignment($incident);
			$incident['group_id'] = $auto_group;
			$incident['assigned'] = $auto_user;
			$incident['status_id'] = $auto_status;
		}


		$res = Incident::create($incident);

		if ($res)
		{
			if ($request->has('archivo') /* && $request->file('archivo')->isValid() */)
			{
				foreach ($request->archivo as $file)
				{
					list($temp_name, $name) = explode('__', $file);
					$path = 'attachments/'.$res->id.'/0';
					Storage::move($temp_name, $path.'/'.$name);

					Storage::deleteDirectory(dirname($temp_name));
		
					/* TemporaryFile::where('filename', $temp_name.'__'.$name)
						->delete(); */

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
		$sla_notify = Sla::first()->sla_notify;
		$priorities = Priority::selectRaw('id as pid, description as pdesc, min, max');

		$incident = Incident::with(['client', 'status', 'progress', 'assigned_user', 'assigned_group', 'creator_user'])
			->selectRaw('incidents.*, pr.pid, pr.pdesc, areas.description as area_desc, 
				modules.description as module_desc, problems.description as problem_desc')
			->leftJoinSub($priorities, 'pr', function ($join) {
				$join->on('incidents.priority', '>=', 'pr.min');
				$join->on('incidents.priority', '<=', 'pr.max');
			})
			->leftJoin('areas', 'areas.id', '=', 'area_id')
			->leftJoin('modules', 'modules.id', '=', 'module_id')
			->leftJoin('problems', 'problems.id', '=', 'module_id')
			->findOrFail($id);

		$this->authorize('ver_inc', $incident);

		$groups = Group::orderBy('description')->pluck('description', 'id')->toArray();
		$status = IncidentState::whereBetween('id', [0, 10])->get();
		$users = User::get()->pluck('name', 'id')->toArray();
		
		$progress_types = ProgressType::whereNot('id', 101)->get();

		if (Auth::user()->type==0)
			$clients = Client::where('id', Auth::user()->client_id)->get()->toArray();

		else
			$clients = Client::actives()->toArray();

		return view('incident-edit', compact('incident', 'clients', 'status', 'groups', 
			'progress_types', 'users', 'sla_notify'));

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
		$newrequest['priority'] = $this->getPriorityPoints($newrequest);
		$newrequest['sla'] = $this->getSlaHours($newrequest);

		if ($incident->update($newrequest))
		{
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}

	}

	/* public function cargaMasiva()
	{
		return view('masiva');
	}

	public function procesar(Request $request)
	{
		# Guardamos el archivo a procesar
		$name =  strtolower($request->file('archivo')->name());
        $extension =  strtolower($request->file('archivo')->extension());
        $newfile = Storage::path('archivos').'/'.$name;

        if (!$request->file('archivo')->isValid())
        {
            return back()->with('error', 'Verifique el archivo a procesar');
        }

        $request->file('archivo')->storeAs('archivos', $name);


		# Cargamos el archivo en un array
        $datos = null;
        
        if ($extension == 'xls')
        {
            if ( $xls = SimpleXLS::parse($newfile) ) {
                $datos = $xls->rows();
            } else {
                echo SimpleXLS::parseError();
            }
        }
        elseif ($extension == 'xlsx')
        {
            if ( $xls = SimpleXLSX::parse($newfile) ) {
                $datos = $xls->rows();
            } else {
                echo SimpleXLSX::parseError();
            }
        }
        else
        {
            return back()->with('error', 'No se puede procesar el archivo');
        }


		# Procesamos el archivo usando la cabecera como KEY
		$final = array();
		$cabeceras = array_shift($datos);

		for ($i=0; $i<count($datos); ++$i)
		{
			$linea = array();
			for ($k=0; $k<count($cabeceras); ++$k)
			{
				$cabecera = trim(strtolower($cabeceras[$k]));
				$linea[$cabecera] = (is_string($datos[$i][$k]) && $datos[$i][$k]=='')? null: $datos[$i][$k];
			}
			$final[] = $linea;
		}

		
		//dd($final); 

		# Subimos los datos a MySQL

		$result = true;

		foreach ($final as $dato)
		{

			$date = strtotime(substr($dato['fecha'], 0, 10).' '.substr($dato['hora'], -8));

			$cliente = Cliente::where('descripcion', $dato['cliente'])->first();

			$inc = new Incidente;
			$inc->cliente = (int)$cliente->codigo;
			$inc->area = (int)$cliente->areas()->first()->codigo;
			$inc->modulo = 65;
			$inc->programa = 0;
			$inc->tipo_incidente = 16;
			$inc->titulo = $dato['titulo'];
			$inc->descripcion = $dato['descripcion'];
			$inc->menu = '';
			$inc->usuario = Auth::user()->Usuario;
			$inc->asignado = Auth::user()->Usuario;
			$inc->punto_menu = 0;
			$inc->mail = '';
			$inc->tel = '';
			$inc->status = 10;
			$inc->fecha_ingreso = date('Y-m-d H:i', $date);
			$inc->prioridad = 40;

			//dd($inc); exit();
			if (!$inc->save())
				$result = false;
		}

		if ($result)
			return back()->with('message', 'Se guardaron los registros correctamente');
		else
			return back()->with('error', 'Hubo un error al guardar los registros');

	}

	public function descargarExcel()
	{
		Storage::download("plantilla_incidentes.xlsx");
	} */

	public function donwloadAttachment($incident_id, $progress_id, $attachment)
	{
		return Storage::response("attachments/$incident_id/$progress_id/$attachment");
		//return Storage::download("attachments/$incident_id/$progress_id/$attachment");
	}


}