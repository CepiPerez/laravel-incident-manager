<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sla;
use App\Models\SlaRule;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        $this->authorize('isadmin');

        $data = Sla::first();
        $rules = SlaRule::orderBy('description')->get();
        
        return view('admin.sla', compact('data', 'rules'));
    }

    public function update(Request $request)
    {
        $this->authorize('isadmin');

        $data = Sla::first();
        $data->sla_default = $request->sla;
        $data->sla_notify = $request->sla_notify;

        if ($data->save())
        {
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}

    }

    public static function getRuleValue($rule, $inc)
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

}
