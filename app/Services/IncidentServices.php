<?php

namespace App\Services;

use App\Filters\IncidentFilters;
use App\Models\Area;
use App\Models\Assignation;
use App\Models\Client;
use App\Models\Incident;
use App\Models\Module;
use App\Models\PriorityRule;
use App\Models\Problem;
use App\Models\Sla;
use App\Models\SlaRule;

class IncidentServices
{
	public $incident_filters;

	public function __construct(IncidentFilters $incident_filters)
	{
		$this->incident_filters = $incident_filters;	
	}

    private function getPriorityRulePoints($rule, $inc)
	{
		foreach ($rule->conditions as $cond)
		{
			if ($cond->condition == 'clients')
            {
                if ($inc['client_id']!=$cond->value)
                    return null;
            }

            if ($cond->condition == 'ext_users')
            {
                if ($inc['creator']!=$cond->value)
                    return null;
            }

        }
		return $rule->points;
	}

    private function getSlaRuleValue($rule, $inc)
	{
		foreach ($rule->conditions as $cond)
		{
			if ($cond->condition == 'service_types')
            {
                if (Client::find($inc['client_id'])->service_type_id != $cond->value)
                    return null;
            }

			if ($cond->condition == 'clients')
            {
                if ($inc['client_id']!=$cond->value)
                    return null;
            }

            if ($cond->condition == 'areas')
            {
                if ($inc['area_id']!=$cond->value)
                    return null;
            }

            if ($cond->condition == 'modules')
            {
                if ($inc['module_id']!=$cond->value)
                    return null;
            }

            if ($cond->condition == 'problems')
            {
                if ($inc['problem_id']!=$cond->value)
                    return null;
            }

        }

		return $rule->sla;
	}

    private function getAssignemntRuleValue($rule, $inc)
	{
        $found = false;

		foreach ($rule->conditions as $cond)
		{

			if ($cond->condition == 'clients')
            {
                $found = $inc['client_id']==$cond->value;
            }

            elseif ($cond->condition == 'areas')
            {
                $found = $inc['area_id']==$cond->value;
            }

            elseif ($cond->condition == 'modules')
            {
                $found = $inc['module_id']==$cond->value;
            }

            elseif ($cond->condition == 'problems')
            {
                $found = $inc['problem_id']==$cond->value;
            }

        }

		return $found? [$rule->group_id, $rule->user_id, ($rule->user_id==0? 0 : 1)] : null;
	}

	public function getIncidentList($request_filters)
    {
		//$filters = $this->getMixedFilters($request_filters);

        $incidents = Incident::selectRaw('incidents.id, incidents.client_id, incidents.title, 
            incidents.description, incidents.group_id, incidents.creator, 
            incidents.assigned, incidents.status_id, incidents.priority,
            incidents.created_at, clients.description as client_desc, 
            incident_states.description as status_desc, priorities.id as pid, 
            priorities.description as pdesc, users.name as assigned_name')
            ->leftJoin('incident_states', 'incident_states.id', '=', 'status_id')
            ->leftJoin('clients', 'clients.id', '=', 'client_id')
            ->leftJoin('users', 'users.id', '=', 'assigned')
            ->leftJoin('priorities', function ($join) {
                $join->on('incidents.priority', '>=', 'priorities.min');
                $join->on('incidents.priority', '<=', 'priorities.max');
            });

		return $incidents->filter($this->incident_filters, $request_filters);

    }

    public function getPriorityPoints(array $inc)
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
			$points += $this->getPriorityRulePoints($rule, $inc);
		}

		return $points;
	}

    public function getSlaHours(array $inc)
	{
		$rules = SlaRule::with('conditions')->where('active', 1)->get();

		$sla = Sla::first()->sla_default;

		foreach ($rules as $rule)
		{
			$new_sla = $this->getSlaRuleValue($rule, $inc);

			if ($new_sla)
			{
				$sla = $new_sla;
				break;
			}
		}

		return $sla;
	}

    public function getAssignment(array $inc)
	{
		$rules = Assignation::with('conditions')->where('active', 1)->get();

		foreach ($rules as $rule)
		{
			$new_assignemt = $this->getAssignemntRuleValue($rule, $inc);

			if ($new_assignemt)
				return $new_assignemt;
		}

		return [0, 0, 0];
	}

}