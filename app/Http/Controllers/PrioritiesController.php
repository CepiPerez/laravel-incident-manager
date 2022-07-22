<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Priority;
use App\Models\PriorityRule;
use Illuminate\Http\Request;

class PrioritiesController extends Controller
{
	public function index()
	{
		$this->authorize('isadmin');

		$priorities = Priority::get();
		$rules = PriorityRule::orderBy('description')->get();

		return view('admin.priorities', compact('priorities', 'rules'));
	}

	public function edit($id)
	{
		$this->authorize('isadmin');

		$priority = Priority::find($id);
		
		return view('admin.priorities-edit', compact('priority'));
	}

	public function update(Request $request, $id)
	{
		$this->authorize('isadmin');

		$priority = Priority::find($id);
		$priority->description = $request->description;
		$priority->min = (int)$request->min;
		$priority->max = (int)$request->max;

		$res = $priority->save();

		if ($res)
		{
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}
	}

	public static function getRulePoints($rule, $inc)
	{
		foreach ($rule->conditions as $cond)
		{
			if ($cond->condition == 'clients')
            {
				//dump("CLIENT: " . $cond->value . "::" . $rule->points);
                if ($inc['client_id']!=$cond->value)
                    return null;
            }

            if ($cond->condition == 'ext_users')
            {
				//dump("USER: " . $cond->value . "::" . $rule->points);
                if ($inc['creator']!=$cond->value)
                    return null;
            }

        }
		//dump($rule->points);
		return $rule->points;
	}

}