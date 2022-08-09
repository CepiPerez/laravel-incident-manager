<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sla;
use App\Models\SlaRule;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        $this->authorize('isadmin');

        $data = Sla::first();
        $rules = SlaRule::orderBy('description')->get();
        
        return view('admin.sla', compact('data', 'rules'));
    }

    public function update(Request $request)
    {
        $this->authorize('isadmin');

        $data = Sla::first();
        $data->sla_default = $request->sla;
        $data->sla_notify = $request->sla_notify;

        if ($data->save())
        {
			return back()->with('message', __('main.common.updated'));
		}
		else
		{
			return back()->with('error', __('main.common.error_updating'));
		}

    }

}
