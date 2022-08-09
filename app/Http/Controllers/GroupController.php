<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
	{
		$groups = Group::selectRaw('groups.*, COALESCE(u.cnt,0) AS members, COALESCE(i.cnt2,0) AS counter')
			->joinSub('SELECT group_id, count(group_id) cnt FROM group_user GROUP BY group_id', 'u',
				'u.group_id', '=', 'groups.id', 'LEFT')
			->joinSub('SELECT group_id, count(group_id) cnt2 FROM incidents GROUP BY group_id', 'i',
				'i.group_id', '=', 'groups.id', 'LEFT')
			->orderBy('description')
			->paginate(20);
			

		return view('admin.groups', compact('groups'));
	}

	public function create()
	{
		return view('admin.groups-create');
	}

	public function store(Request $request)
	{
		
		$request->validate([
			'description' => 'required|max:100|unique:groups,description'
		]);
		
		$group = Group::create(['description' => $request->description]);

		if ($request->users)
		{
			$users = []; 
			foreach ($request->users as $key => $val)
				$users[] = $val;
			
			$group->users()->sync($users);
		}

		if ($group)
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
		$group = Group::with('users')->find($id);

		return view('admin.groups-edit', compact('group'));
	}

	public function update(Request $request, $id)
	{
		$group = Group::find($id);
		$group->description = $request->description;
		
		if ($request->users)
		{
			$users = array(); 
			foreach ($request->users as $key => $val)
				$users[] = $val;
			
			$group->users()->sync($users);
		}
		else
		{
			$group->users()->sync([]);
		}

		if ($group->save())
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
		$group = Group::find($id);

		if ($group->delete())
		{
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}

}
