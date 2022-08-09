<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{

	public function edit($id)
	{
		if (Auth::user()->id != $id) abort(403);

		$user = User::findOrFail($id);

		return view('user-profile', compact('user'));
	}

	public function update(Request $request, $id)
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
			//dd($request->all());

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