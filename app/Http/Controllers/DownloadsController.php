<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadsController extends Controller
{
	public function download($file_type, $file_name) {
		if($file_type == 1)
		{
			$file_path = public_path('files/'.$file_name);
		}

		if($file_type == 2)
		{
			$fileexplode = explode('!!$', $file_name);
			$file_path = public_path('storage/CRD/'.$fileexplode[0].'/'.$fileexplode[1]);
		}
    	
    	
    	return response()->download($file_path);
  	}
}
