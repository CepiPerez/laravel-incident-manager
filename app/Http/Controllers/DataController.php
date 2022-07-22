<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Client;
use App\Models\Group;
use App\Models\Module;
use App\Models\Problem;
use App\Models\ServiceType;
use App\Models\User;

class DataController extends Controller
{

    public function getData($data, $param)
    {
        $response = array();

        $res = array();

        if ($data=='areas') 
            $res = $param=='a'? Area::orderBy('description')->get() : Client::find($param)->areas;

        elseif ($data=='modules') 
            $res = $param=='a'? Module::orderBy('description')->get() : Area::find($param)->modules;

        elseif ($data=='problems') 
            $res = $param=='a'? Problem::orderBy('description')->get() : Module::find($param)->problems;

        elseif ($data=='clients') 
            $res = $param=='a'? Client::orderBy('description')->get() : null;

        elseif ($data=='service_types')  
            $res = $param=='a'? ServiceType::orderBy('description')->get() : null;

        elseif ($data=='groups')  
            $res = Group::orderBy('description')->get();

        elseif ($data=='users') 
        {
            if ($param==0)
                $response[] = array(
                    "id"    => 0,
                    "text"  => __('main.incidents.unnasigned_selection')
                );

            else
            {
                if ($param=='a')
                {
                    $res = User::actives();
                }
                else
                {
                    $res = Group::find($param)->users;
                    $response[] = array(
                        "id"    => 0,
                        "text"  => __('main.incidents.unnasigned_selection')
                    );
                }
            }
        }
        elseif ($data=='ext_users') 
        {
            $res = User::where('active', 1)->orderBy('name')->get();
        }

        foreach($res as $r){
            $response[] = array(
                "id"    => $r->id,
                "text"  => $r->description ?? $r->name
            );
        }
        return response()->json($response); 

    }

}
