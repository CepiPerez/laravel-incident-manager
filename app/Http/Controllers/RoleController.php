<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
	
	public function index()
	{
		$roles = Role::selectRaw('roles.*, COALESCE(i.cnt,0) AS counter')
		->joinSub('SELECT role_id, count(role_id) cnt FROM users GROUP BY role_id', 'i',
			'i.role_id', '=', 'roles.id', 'LEFT')
		->orderBy('description')
		->paginate(20);

		return view('admin.roles', compact('roles'));
	}

	public function create()
	{
		$permissions = Permission::get();

		return view('admin.role-create', compact('permissions'));
	}

	public function store(Request $request)
	{

		$request->validate([
			'description' => 'required|max:50|unique:roles,description'
		]);

		$role = new Role;
		$role->description = $request->description;
		$role->type = $request->type;
		$res = $role->save();

		$this->upadtePermissions($role, $request);


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
		$role = Role::find($id);

		$permissions = Permission::get();
		$permissions_role = $role->permissions->pluck('id')->toArray();

		return view('admin.role-edit', compact('role', 'permissions', 'permissions_role'));
	}

	public function update(Request $request, $id)
	{
		$role = Role::find($id);
		$role->description = $request->description;
		$role->type = $request->type;
		$res = $role->save();

		$this->upadtePermissions($role, $request);

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
		$res = Role::find($id);

		if ($res->delete())
		{
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}

	private function upadtePermissions($role, $request)
	{
		$permissions = array();

		if ($request->permissions)
		{
			foreach ($request->permissions as $val)
			{
				$permissions[] = $val;
			}
		}

		if ($request->permissions_adm && $request->type==1)
		{
			foreach ($request->permissions_adm as $val)
			{
				$permissions[] = $val;
			}
		}

		$role->permissions()->sync($permissions);

	}

}