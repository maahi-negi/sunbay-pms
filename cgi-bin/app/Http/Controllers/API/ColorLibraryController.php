<?php

namespace App\Http\Controllers\API;

use App\Models\Homes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\ColorLibrary;
use File;
class ColorLibraryController extends Controller
{


    public function createColorLibrary(Request $request)
    {

        if($request->sw_code)
        {
            $color_lib = ColorLibrary::create([
                'sw_code' => $request->sw_code,
                'rgb' =>$request->rgb_input,
                'hex' => $request->hex_code,
                'status_id' => $request->status,
            ]);
            return $color_lib;
        }
    }

    public function modifyColorLibrary(Request $request)
    {
        $color_lib = ColorLibrary::find($request->color_id);

        if($request->sw_code) {
            $color_lib->sw_code = $request->sw_code;
        }
        if($request->hex_code) {
            $color_lib->hex = $request->hex_code;
        }
        if($request->rgb_input) {
            $color_lib->rgb = $request->rgb_input;
        }
        $color_lib->status_id = $request->status;
        $color_lib->save();
        return $color_lib;
    }

    public function deleteColorLibrary(Request $request){
        $color_lib = ColorLibrary::find($request->color_id);
        $color_lib->status_id = 2;
        $color_lib->save();
    }

    public function getColorLibrary(Request $request)
    {
        $query = $request->get('query');
        $color_lib = ColorLibrary::where('status_id', '!=', 2)->where('sw_code', 'like', "%$query%")->get();
        return response()->json($color_lib);
    }
}
