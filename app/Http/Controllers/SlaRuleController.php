<?php

namespace App\Http\Controllers;

use App\Models\SlaRule;
use App\Models\SlaRuleCondition;
use Illuminate\Http\Request;

class SlaRuleController extends Controller
{
	
    public function create()
	{
		return view('admin.slarule-create');
	}

	public function store(Request $request)
	{

		$request->validate([
			'description' => 'required|max:150',
			'conditions' => 'required'
		]);

		$regla = array();
		$regla['description'] = $request->description;
		$regla['sla'] = $request->sla;
		$regla['active'] = 1; 
		$res = SlaRule::create($regla);

		SlaRuleCondition::where('rule_id', $res->id)->delete();

		for ($i=0; $i < count($request->conditions); ++$i)
		{
			$rc = new SlaRuleCondition;
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
		$rule = SlaRule::with('conditions')->find($id);

		return view('admin.slarule-edit', compact('rule'));
	}

	public function update(Request $request, $id)
	{
		$regla = SlaRule::find($id);

		$request->validate([
			'description' => 'required|max:150',
			'conditions' => 'required|min:1'
		]);

		$regla->description = $request->description;
		$regla->sla = $request->sla;
		$regla->active = $request->active;
		$res = $regla->save();

		SlaRuleCondition::where('rule_id', $id)->delete();

		for ($i=0; $i < count($request->conditions); ++$i)
		{
			$rc = new SlaRuleCondition;
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
		$regla = SlaRule::find($id);
		$res = $regla->delete();

		if ($res) {
			$res = SlaRuleCondition::where('rule_id', $id)->delete();
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
