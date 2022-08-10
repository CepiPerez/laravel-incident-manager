<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{

	public function index()
	{
		
		$users = User::with(['role'])
			->selectRaw('users.*, COALESCE(i.cnt,0) AS created, COALESCE(i2.cnt,0) AS assigned')
			->joinSub('SELECT creator, count(creator) cnt FROM incidents GROUP BY creator', 'i',
				'i.creator', '=', 'users.id', 'LEFT')
			->joinSub('SELECT assigned, count(assigned) cnt FROM incidents GROUP BY assigned', 'i2',
				'i2.assigned', '=', 'users.id', 'LEFT')
			->orderBy('name')
			->paginate(20);

		foreach ($users as $user)
		{
			$user->counter = $user->created + $user->assigned;
		}

		return view('admin.users', compact('users'));
	}

	public function create()
	{
		$clients = Client::actives();
		$groups = Group::orderBy('description')->get();
		$roles = Role::get();

		return view('admin.user-create', compact('clients', 'groups', 'roles'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'username' => 'required|max:30|unique:users,username',
			'name' => 'required|max:150',
			'password' => 'required|max:20'
		]);

		$user = new User;
		$user->username = $request->username;
		$user->name = $request->name;
		$user->password = Hash::make($request->password);
		$user->email = $request->email;
		$user->role_id = $request->role;
		$user->type = $request->type;
		$user->client_id = $request->type==0? $request->client_id : 0;
		$user->active = 1;
		$res = $user->save();

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
		$user = User::find($id);
		$clients = Client::actives();
		$groups = Group::orderBy('description')->get();
		$roles = Role::get();

		return view('admin.user-edit', compact('user', 'clients', 'groups', 'roles'));
	}

	public function update(Request $request, $id)
	{
		$user = User::find($id);

		$request->validate([
			'username' => 'required|max:30|unique:users,username,'.$user->id,
			'email' => 'required|max:50|unique:users,email,'.$user->id,
			'name' => 'required|max:150'
		]);

		$user->username = $request->username;
		$user->name = $request->name;
		$user->email = $request->email;
		if ($request->password!='') $user->password = Hash::make($request->password);
		$user->role_id = $request->role;
		$user->type = $request->type;
		$user->client_id = $request->type==0? $request->client_id : 0;
		$user->active = $request->active;
		$res = $user->save();

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
		$res = User::find($id);
		
		if ($res->delete())
		{
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}
	}

	public function profile($id)
	{
		if (Auth::user()->id != $id) abort(403);

		$user = User::findOrFail($id);

		return view('user-profile', compact('user'));
	}

	public function updateProfile(Request $request, $id)
	{

		if (Auth::user()->id != $id) abort(403);

		$request->validate([
			'email' => 'required|max:50|unique:users,email,'.Auth::user()->id,
			'name' => 'required|max:150'
		]);
		
		$user = User::find($id);
		$user->name = $request->name;
		$user->email = $request->email;
		if ($request->password!='') $user->password = Hash::make($request->password);
		$res = $user->save();

		if ($request->hasFile('avatar')	)
		{
			if (Storage::exists('profile/'.$id.'.png'))
				Storage::delete('profile/'.$id.'.png');

			if (Storage::exists('profile/'.$id.'.jpg'))
				Storage::delete('profile/'.$id.'.jpg');

			if (Storage::exists('profile/'.$id.'.webp'))
				Storage::delete('profile/'.$id.'.webp');

			$ext = $request->file('avatar')->extension();
			$ext = str_replace('jpeg', 'jpg', strtolower($ext));

			$request->file('avatar')->storeAs('profile', "$id.$ext");
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

}