<?php

namespace App\Http\Controllers\API;

use App\Models\Homes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\PatternLibrary;
use File;
class PatternLibraryController extends Controller
{


    public function createPatternLibrary(Request $request)
    {

        $destination_path = public_path('media/uploads/exterior');
        if($request->thumbnail) {
            $thumbnail_file = $request->file('thumbnail');
            $ext = $thumbnail_file->getClientOriginalExtension();
            $thumbnail_name = time()."-thumb.$ext";
            $thumbnail_file->move($destination_path, $thumbnail_name);
        } else {
            $thumbnail_name = null;
        }
        if($request->additional_thumbs && $request->additional_thumbs != 'undefined') {
            $additional_thumbnail_file = $request->file('additional_thumbs');
            //$additional_thumbnail_file->getSize();
            $ext = $additional_thumbnail_file->getClientOriginalExtension();
            $additional_thumbnail_name = time()."-thumb.$ext";
            $additional_thumbnail_file->move($destination_path, $additional_thumbnail_name);
        } else {
            $additional_thumbnail_file = null;
        }


        if($request->title)
        {
            $pattern_lib = PatternLibrary::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'product_id' => $request->product_id,
                'thumbnail' => $thumbnail_name,
                'additional_thumbs' => $additional_thumbnail_file,
                'status_id' => $request->status,
            ]);
            return $pattern_lib;
        }
    }

    public function modifyPatternLibrary(Request $request)
    {
        $color_lib = PatternLibrary::find($request->color_id);

        if($request->title) {
            $color_lib->title = $request->title;
        }
        if($request->product_id) {
            $color_lib->product_id = $request->product_id;
        }
        if($request->thumbnail) {
            $color_lib->thumbnail = $request->thumbnail;
        }
        $color_lib->status_id = $request->status;
        $color_lib->save();
        return $color_lib;
    }

    public function deletePatternLibrary(Request $request){
        $color_lib = PatternLibrary::find($request->color_id);
        $color_lib->status_id = 2;
        $color_lib->save();
    }

    public function getPatternLibrary(Request $request)
    {
        $query = $request->get('query');
        $color_lib = PatternLibrary::where('status_id', '!=', 2)->where('sw_code', 'like', "%$query%")->get();
        return response()->json($color_lib);
    }
}
