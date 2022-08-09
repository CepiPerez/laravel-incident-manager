<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {

        /* if ($request->header("_method") == "DELETE")
            return Storage::deleteDirectory(dirname(request()->getContent())); */


        if ($request->hasFile('archivo') /* && $request->file('archivo')->isValid() */)
        {
            $files = $request->file('archivo'); 

            foreach ($files as $file)
            {
                $name = $file->getClientOriginalName();
                $path = 'attachments/temp/'.uniqid().'/';
                $temp_name = uniqid().'-'.now()->timestamp;
                $file->move($path, $temp_name);
    
                /* TemporaryFile::create([
                    'folder' => $path,
                    'filename' => $temp_name
                ]); */

                return $path.$temp_name."__".$name;
            }
    
        }
        
        return '';

    }

    public function destroy()
    {
        return Storage::deleteDirectory(dirname(request()->filename));
    }

    public function download($incident_id, $progress_id, $attachment)
	{
		return Storage::response("attachments/$incident_id/$progress_id/$attachment");
		//return Storage::download("attachments/$incident_id/$progress_id/$attachment");
	}

}
