<?php

namespace App\Http\Controllers;

use App\Models\Assignation;
use App\Models\AssignationCondition;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class AssignationController extends Controller
{

	public function index()
    {
        $this->authorize('isadmin');

        $rules = Assignation::selectRaw('assignations.*, groups.description as group_name, users.username as user_name')
            ->leftJoin('groups', 'groups.id', 'assignations.group_id')
            ->leftJoin('users', 'users.id', 'assignations.user_id')
            ->orderBy('description')->get();
    
        return view('admin.assignation', compact('rules'));
    }

    public function create()
	{
        $this->authorize('isadmin');

        $groups = Group::orderBy('description')->get()->toArray();

		return view('admin.assignation-create', compact('groups'));
	}

    public function store(Request $request)
	{
		$this->authorize('isadmin');

		$request->validate([
			'description' => 'required|max:150',
			'conditions' => 'required'
		]);

		$regla = array();
		$regla['description'] = $request->description;
		$regla['group_id'] = $request->group_id;
		$regla['user_id'] = $request->user_id;
		$regla['active'] = 1; 
		$res = Assignation::create($regla);

		AssignationCondition::where('rule_id', $res->id)->delete();

		for ($i=0; $i < count($request->conditions); ++$i)
		{
			$rc = new AssignationCondition;
			$rc->rule_id = $res->id;
			$rc->condition = $request->conditions[$i];
			$rc->value = $request->values[$i];
			$rc->helper = $request->text[$i];
			$rc->save();
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
        $this->authorize('isadmin');

		$rule = Assignation::with('conditions')->find($id);

        $groups = Group::orderBy('description')->get()->toArray();
        $users = Group::find($rule->group_id)->users->toArray();

		return view('admin.assignation-edit', compact('rule', 'groups', 'users'));
	}

    public function update(Request $request, $id)
	{
		$regla = Assignation::find($id);

		$request->validate([
			'description' => 'required|max:150',
			'conditions' => 'required|min:1'
		]);

		$regla->description = $request->description;
		$regla->group_id = $request->group_id;
		$regla->user_id = $request->user_id;
		$regla->active = $request->active;
		$res = $regla->save();

		AssignationCondition::where('rule_id', $id)->delete();

		for ($i=0; $i < count($request->conditions); ++$i)
		{
			$rc = new AssignationCondition();
			$rc->rule_id = $id;
			$rc->condition = $request->conditions[$i];
			$rc->value = $request->values[$i];
			$rc->helper = $request->text[$i];
			$rc->save();
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
		$regla = Assignation::find($id);
		$res = $regla->delete();

		if ($res) {
			$res = AssignationCondition::where('rule_id', $id)->delete();
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
