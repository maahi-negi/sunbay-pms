<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Homes;
use App\Models\Elevations;
use App\Models\HomeDesignTypes;
use App\Models\HomeDesigns;
use File;
class ExteriorController extends Controller
{
    public function createHome(Request $request)
    {
        $destination_path = public_path('media/uploads/exterior');
        if($request->file('base_image'))
        {
            $view1_file = $request->file('base_image');
            $view1_name = $view1_file->getClientOriginalName();
            $view1_file->move($destination_path, $view1_name);
        }

        else
        {
            $view1_name = null;
        }

        if($request->title)
        {
            $homeplan = Homes::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'base_image' => ($view1_name)?$view1_name:'',
                'status_id' => $request->status,
                'floorplan' => $request->floorplan,
                'exterior' => $request->exterior
            ]);
            return $homeplan;
        }
    }

    public function modifyHome(Request $request)
    {
        $homeplan = Homes::find($request->home_id);

        if($request->title)
        {
            $homeplan->title = $request->title;
        }

        $homeplan->status_id = $request->status;
        $homeplan->floorplan = $request->floorplan;
        $homeplan->exterior = $request->exterior;

        $destination_path = public_path('media/uploads/exterior');
        if($request->file('base_image'))
        {
            $view1_file = $request->file('base_image');
            $view1_name = $view1_file->getClientOriginalName();
            $view1_file->move($destination_path, $view1_name);
            $homeplan->base_image = $view1_name;
        }

        $homeplan->save();
        return $homeplan;
    }

    public function deleteHome(Request $request){
        $homeplan = Homes::find($request->home_id);
        $homeplan->status_id = 2;
        $homeplan->save();
        HomeDesignTypes::where('elevation_id',$homeplan->id)->update(['status_id'=>"2"]);
        HomeDesigns::where('elevation_id',$homeplan->id)->update(['status_id'=>"2"]);
    }
    

    public function createElevation(Request $request)
    {
        $destination_path = public_path('media/uploads/exterior');
        if($request->file('base_image'))
        {
            $view1_file = $request->file('base_image');
            $view1_name = $view1_file->getClientOriginalName();
            $view1_file->move($destination_path, $view1_name);
        }

        else
        {
            $view1_name = null;
        }

        if($request->title)
        {
            $elevation = Elevations::create([
                'home_id' => base64_decode($request->home_id),
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'base_image' => ($view1_name)?$view1_name:'',
                'status_id' => $request->status,
                'floorplan' => ($request->floorplan == 1)?1:0,
                'exterior' => ($request->exterior == 1)?1:0
            ]);
            $elevation->home_exterior = Homes::where('id', $elevation->home_id)->first()->exterior;
            $elevation->home_floorplan = Homes::where('id', $elevation->home_id)->first()->floorplan;
            return $elevation;
        }
    }

    public function duplicateElevation(Request $request)
    {
        $oelevation = Elevations::find($request->elevation_id);
        $elevation = Elevations::create([
            'home_id' => $oelevation->home_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'base_image' => $oelevation->base_image,
            'status_id' => $oelevation->status_id,
            'floorplan' => $oelevation->floorplan,
            'exterior' => $oelevation->exterior
        ]);

        if($request->duplicatetype >= 2)
        {
            $home_design_types = HomeDesignTypes::where('elevation_id', $request->elevation_id)->get();
            if($home_design_types) {
                foreach ($home_design_types as $hdt) {
                    $slug = Str::slug($hdt->title, '-');
                    $newdtype = HomeDesignTypes::create([
                                        'title' => $hdt->title,
                                        'slug' => $slug,
                                        'elevation_id' => $elevation->id,
                                        'thumbnail' => $hdt->thumbnail,
                                        'priority' => $hdt->priority,
                                        'status_id' => $hdt->status_id,
                                ]);
                    if($request->duplicatetype == 3) {
                        $home_designs = HomeDesigns::where('home_design_type_id', $hdt->id)->get();
                        if($home_designs) {
                            $source_path = public_path('media/uploads/exterior/'.$hdt->slug.'_'.$hdt->id);
                            $destination_path = public_path('media/uploads/exterior/'.$slug.'_'.$newdtype->id);

                            if (!File::exists($destination_path)) {
                                File::makeDirectory($destination_path);
                            }
                            foreach ($home_designs as $hd) {

                                HomeDesigns::create([
                                    'title'             => $hd->title,
                                    'slug'              => $hd->slug,
                                    'elevation_id'      =>  $elevation->id,
                                    'home_design_type_id' => $newdtype->id,
                                    'thumbnail'         => $hd->thumbnail,
                                    'image_view1'       => $hd->image_view1,
                                    'is_default'        => $hd->is_default,
                                    'price'             => $hd->price,
                                    'material'          => $hd->material,
                                    'manufacturer'      => $hd->manufacturer,
                                    'product_id'        => $hd->product_id,
                                    'rating'            => $hd->rating,
                                    'status_id'         => $hd->status_id
                                ]);

                                if($request->showimages == 1) {

                                    if(File::exists($source_path.'/'.$hd->thumbnail)) {
                                        File::copy($source_path.'/'.$hd->thumbnail, $destination_path.'/'.$hd->thumbnail);
                                    }
                                    if(File::exists($source_path.'/'.$hd->image_view1)) {
                                        File::copy($source_path.'/'.$hd->image_view1, $destination_path.'/'.$hd->image_view1);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $elevation->home_exterior = Homes::where('id', $elevation->home_id)->first()->exterior;
            $elevation->home_floorplan = Homes::where('id', $elevation->home_id)->first()->floorplan;
            return $elevation;
        }
        else {
            $elevation->home_exterior = Homes::where('id', $elevation->home_id)->first()->exterior;
            $elevation->home_floorplan = Homes::where('id', $elevation->home_id)->first()->floorplan;
            return $elevation;
        }
    }

    public function modifyElevation(Request $request)
    {
        $elevation = Elevations::find($request->id);

        if($request->title)
        {
            $elevation->title = $request->title;
        }

        $elevation->status_id = $request->status;
        $elevation->floorplan = ($request->floorplan == 1)?1:0;
        $elevation->exterior = ($request->exterior == 1)?1:0;

        $destination_path = public_path('media/uploads/exterior');
        if($request->file('base_image'))
        {
            $view1_file = $request->file('base_image');
            $view1_name = $view1_file->getClientOriginalName();
            $view1_file->move($destination_path, $view1_name);
            $elevation->base_image = $view1_name;
        }

        $elevation->save();
        $elevation->home_exterior = Homes::where('id', $elevation->home_id)->first()->exterior;
        $elevation->home_floorplan = Homes::where('id', $elevation->home_id)->first()->floorplan;
        return $elevation;
    }

    public function deleteElevation(Request $request){
        $elevation = Elevations::find($request->elevation_id);
        $elevation->status_id = 2;
        $elevation->save();
    }
}
