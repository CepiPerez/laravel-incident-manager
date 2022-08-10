<?php

namespace App\Http\Controllers;

use App\Filters\IncidentFilters;
use App\Filters\SlaStatusFilter;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Sla;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithStoredFilters;
use App\Services\IncidentServices;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use WithStoredFilters;
   
    protected $filters;
    protected $incident_filters;
    private $incident_service;

    public function __construct(IncidentServices $service, IncidentFilters $incident_filters)
    {
        $this->incident_filters = $incident_filters;
        $this->filters = $this->getStoredFilters('dashboard_filters');
        $this->incident_service = $service;
    }

    private function getIncidentsCounter($sla)
    {
        $counter = Incident::selectRaw("COUNT(*) AS total,
            SUM(case when status_id=0 then 1 ELSE 0 end) AS unassigned,
            SUM(case when (status_id BETWEEN 1 AND 9) AND (status_id!=5)  then 1 ELSE 0 end) AS in_progress,
            SUM(case when status_id=5 then 1 ELSE 0 end) AS paused,
            SUM(case when status_id=10 then 1 ELSE 0 end) AS resolved,
            SUM(case when status_id=20 then 1 ELSE 0 end) AS closed,
            SUM(case when status_id=50 then 1 ELSE 0 end) AS canceled,
            SUM(case when status_id<10 then 1 ELSE 0 end) AS opened,
            SUM(case when sla=0 then 1 ELSE 0 end) AS without_sla,
            SUM(case when sla>0 then 1 ELSE 0 end) AS with_sla,
            SUM(case when ((DATE_ADD(created_at, INTERVAL sla HOUR)>NOW() AND DATE_ADD(created_at, INTERVAL sla - $sla HOUR)>NOW()) OR sla=0) AND status_id<10 then 1 ELSE 0 end) AS on_time,
            SUM(case when DATE_ADD(created_at, INTERVAL sla HOUR)>NOW() AND DATE_ADD(created_at, INTERVAL sla - $sla HOUR)<NOW() AND sla>0 AND status_id<10 then 1 ELSE 0 end) AS to_expire,
            SUM(case when DATE_ADD(created_at, INTERVAL sla HOUR)<NOW() AND sla>0 AND status_id<10 then 1 ELSE 0 end) AS expired");

        //dd($counter->filter($this->incident_filters, $filters)->toSql());

        return $counter->filter($this->incident_filters, $this->filters)->first();
    }

    private function getFilteredIncidents($filter, $sla)
    {
        $incidents = $this->incident_service->getIncidentList($this->filters);

        $incidents = SlaStatusFilter::filter($incidents, $filter, $sla);

        return $incidents->orderBy('id', 'desc')->with('client')->paginate(15);

    }

    public function index($filter = null)
    {
        $this->authorize('tablero_control');

        $sla = Sla::first();

        $counter = $this->getIncidentsCounter($sla->sla_notify ?? 0);

        $incidents = $filter? $this->getFilteredIncidents($filter, $sla->sla_notify ?? 0) : [];

        $status = __('main.dashboard.'.$filter.'_title');

        $groups = Auth::user()->type==1? 
            DB::table('groups')->orderBy('description')->pluck('description', 'id')->toArray() : [];

        $users = Auth::user()->type==1? 
            DB::table('users')->orderBy('name')->pluck('name', 'id')->toArray(): [];


        $filters = $this->filters;

        return view('dashboard', compact('incidents', 'counter', 'status', 'sla', 'groups', 'users', 'filters'));

    }

}
