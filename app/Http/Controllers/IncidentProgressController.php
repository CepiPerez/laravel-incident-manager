<?php

namespace App\Http\Controllers;

use App\Models\IncidentAttachment;
use App\Models\Incident;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncidentProgressController extends Controller
{

	public function store(Request $request, $id)
	{
		//dd($request->all());

		$inc = Incident::find($id);

		$progress = [];
		$progress['incident_id'] = $id;
		$progress['progress_type_id'] = $request->progress_type;
		$progress['description'] = $request->description;
		$progress['user_id'] = Auth::user()->id;

		if ($request->progress_type==1)
		{
			$progress['assigned_to'] = Auth::user()->id;
			$progress['assigned_group_to'] = Auth::user()->groups->first()->id;
		}
		elseif ($request->progress_type==2)
		{
			$progress['assigned_to'] = $request->assign_user ?? null;
			$progress['assigned_group_to'] = $request->assign_group ?? null;
		}		
					
		$progress['created_at'] = date('Y-m-d H:i');
		$progress['prev_assigned'] = $inc->assigned ?? null;
		$progress['prev_assigned_group'] = $inc->group_id ?? null;
		$progress['prev_status'] = $inc->status_id;

		$res = Progress::create($progress);

		if ($res)
		{
			if ($request->has('archivo'))
			{
				foreach ($request->archivo as $file)
				{
					list($temp_name, $name) = explode('__', $file);
					$path = 'attachments/'.$id.'/'.$res->id;
					Storage::move($temp_name, $path.'/'.$name);

					Storage::deleteDirectory(dirname($temp_name));

					IncidentAttachment::create([
						'incident_id' => $id,
						'progress_id' => $res->id,
						'attachment' => $name
					]);
				}
			}

			# Taking
			if ($request->progress_type==1)
			{
				if ($inc->status_id==0)
					$inc->status_id = 1;

				$inc->assigned = Auth::user()->id;
				$inc->group_id = Auth::user()->groups->first()->id;
			}

			# Derivation
			elseif ($request->progress_type==2)
			{
				# If it's assigned to an user then set status to 'in progress'
				if ($inc->status_id==0 && $request->assign_user!=0)
					$inc->status_id = 1;

				$inc->assigned = $request->assign_user;
				$inc->group_id = $request->assign_group;
			}
				
			# Paused
			elseif ($request->progress_type==5)
				$inc->status_id = 5;

			# Re-open
			elseif ($request->progress_type==6)
				$inc->status_id = 1;

			# Resolved
			elseif ($request->progress_type==10)
				$inc->status_id = 10;

			# Closed
			elseif ($request->progress_type==20)
				$inc->status_id = 20;

			# Canceled
			elseif ($request->progress_type==50)
				$inc->status_id = 50;

			$inc->save();


			//$envio = Correo::verificar($res);
			

			return back()->with('message', __('main.common.saved'));
		}
		else
		{
			return back()->with('error', __('main.common.error_saving'));
		}


	}

	public function destroy($incident_id, $progress_id)
	{

		$progress = Progress::where('incident_id', $incident_id)->where('id', $progress_id)->first();

		$incident = Incident::find($incident_id);

		if ($progress->progress_type_id==1 || $progress->progress_type_id==2)
		{
			$incident->assigned = isset($progress->prev_assigned)? $progress->prev_assigned : null;
			$incident->group_id = isset($progress->prev_assigned_group)? $progress->prev_assigned_group : null;
		}

		if (isset($progress->prev_status))
			$incident->status_id = $progress->prev_status;

		$incident->save();
		
		/* $adjunto = AdjuntosIncidente::find($avance); */
		
		if ($progress->delete())
		{
			IncidentAttachment::where('incident_id', $incident_id)->where('progress_id', $progress_id)->delete();
			Storage::deleteDirectory('attachments/'.$incident_id.'/'.$progress_id);
			
			return back()->with('message', __('main.common.deleted'));
		}
		else
		{
			return back()->with('error', __('main.common.error_deleting'));
		}

	}



}