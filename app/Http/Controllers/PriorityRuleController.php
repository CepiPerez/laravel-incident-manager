<?php

namespace App\Http\Controllers;

use App\Models\PriorityRule;
use App\Models\PriorityRuleCondition;
use Illuminate\Http\Request;

class PriorityRuleController extends Controller
{
    public function create()
	{
		return view('admin.priorityrule-create');
	}

	public function store(Request $request)
	{
		$request->validate([
			'description' => 'required|max:150',
			'conditions' => 'required'
		]);

		$regla = array();
		$regla['description'] = $request->description;
		$regla['points'] = $request->points;
		$regla['active'] = 1; 
		$res = PriorityRule::create($regla);

		PriorityRuleCondition::where('rule_id', $res->id)->delete();

		for ($i=0; $i < count($request->conditions); ++$i)
		{
			$rc = new PriorityRuleCondition;
			$rc->rule_id = $res->id;
			$rc->condition = $request->conditions[$i];
			$rc->value = $request->values[$i];
			$rc->helper = $request->text[$i];
			$res = $rc->save();
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
		$rule = PriorityRule::with('conditions')->find($id);

		return view('admin.priorityrule-edit', compact('rule'));
	}

	public function update(Request $request, $id)
	{
		$regla = PriorityRule::find($id);

		$request->validate([
			'description' => 'required|max:150',
			'conditions' => 'required|min:1'
		]);

		$regla->description = $request->description;
		$regla->points = $request->points;
		$regla->active = $request->active;
		$res = $regla->save();

		PriorityRuleCondition::where('rule_id', $id)->delete();

		for ($i=0; $i < count($request->conditions); ++$i)
		{
			$rc = new PriorityRuleCondition;
			$rc->rule_id = $id;
			$rc->condition = $request->conditions[$i];
			$rc->value = $request->values[$i];
			$rc->helper = $request->text[$i];
			$res = $rc->save();
		}

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
		$regla = PriorityRule::find($id);
		$res = $regla->delete();

		if ($res) {
			$res = PriorityRuleCondition::where('rule_id', $id)->delete();
		}

		if ($res)
		{
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}

}
