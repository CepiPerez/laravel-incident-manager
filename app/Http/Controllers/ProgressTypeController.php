<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\ProgressType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressTypeController extends Controller
{
	
	public function index()
	{
		$progress_types = ProgressType::orderBy('description')->paginate(20);

		return view('admin.progresstypes', compact('progress_types'));
	}

	public function edit($id)
	{
		$progress_type = ProgressType::find($id);

		$template = null;

		if (Storage::exists('public/templates_correo/progress_type_'.$id.'.html'))
			$template = Storage::get('public/templates_correo/progress_type_'.$id.'.html');

		return view('admin.progresstype-edit', compact('progress_type', 'template'));
	}

	public function update(Request $request, $id)
	{
		$tipo_avance = ProgressType::find($id);
		$tipo_avance->description = $request->description;
		$tipo_avance->client_visible = $request->visible=='on'? 1 : 0;
		$tipo_avance->send_email = $request->email=='on'? 1 : 0;

		$res = $tipo_avance->save();

		if ($res)
		{
			if ($request->html)
				Storage::put('public/templates_correo/tipo_avance_'.$id.'.html', $request->html);

			return back()->with('message', 'Se guardaron los cambios correctamente');
		}
		else
		{
			return back()->with('error', 'Hubo un error al guardar los cambios');
		}
	}

}