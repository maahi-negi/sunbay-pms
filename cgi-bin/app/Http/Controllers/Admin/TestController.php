<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HomeDesignTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TestController extends Controller
{
    public $data;
    public function test()
    {
        die;
        $designs = HomeDesignTypes::where('status_id', 1)->get();
        foreach($designs as $design) {
           $path = public_path('media/uploads/exterior/'.$design->slug.'_'.$design->id);
           if(!File::isDirectory($path)){
                File::makeDirectory($path, 0755, true, true);
            }
        }
        echo 1; die;
    }
}
