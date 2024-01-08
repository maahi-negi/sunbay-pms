<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeDesignTypes;
use App\Models\Homes;
use App\Models\Elevations;
use App\Models\HomeDesigns;
use App\Models\Settings;

class ExteriorController extends Controller
{
    // home page
    public function index($slug = null){
        $this->data['typepath'] = 'media/thumbnails/';
        $this->data['designpath'] = 'media/uploads/exterior/';
        $this->data['allSettings'] = Settings::orderBy('id')->get()->toArray();

        $home = Homes::where("status_id","1")->first();
        if($slug){
            $home = Homes::where('slug', $slug)->where("status_id","1")->first();
        }
        if($home) {
            $sources1 = [];
            $sources2 = [];
            $this->data['design_group'] = $home;

            $design_types = HomeDesignTypes::where(['status_id' => 1, 'elevation_id' => $home->id])->orderBy("priority","asc")->with('designs');
            $this->data['design_types'] = $design_types->get();
            if($home->base_image) {
                $sources1['base_image_view1'] = asset('media/uploads/exterior/'.$home->base_image);
            }
            foreach($design_types->orderBy('priority', 'desc')->get() as $design_type) {
                $design = HomeDesigns::where([
                    'home_design_type_id'    => $design_type->id,
                    'status_id'         => 1,
                    'is_default'        => 1
                ])->first();
                if(isset($design->base_image))
                {
                    $sources1[$design_type->slug] = asset('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id.'/'.$design->image_view1);
                }
            }
            if($home->base_image){
                $sources2['base_image_view2'] = asset('media/uploads/exterior/'.$home->base_image);
            }
            foreach($design_types->orderBy('priority', 'desc')->get() as $design_type)
            {
                $design = HomeDesigns::where([
                    'home_design_type_id'    => $design_type->id,
                    'status_id'         => 1,
                    'is_default'        => 1
                ])->first();
                if(isset($design->image_view2))
                {
                    $sources2[$design_type->slug] = asset('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id.'/'.$design->image_view2);
                }
            }
            $this->data['sources1'] = $sources1;
            $this->data['sources2'] = $sources2;
            return view('exterior')->with($this->data);
        }
    }

    public function homes()
    {
        $this->data['menu'] = 'homeplans';
        $user = auth()->user();
        if(auth()->check()) {
            $this->data['homes'] = Homes::where('status_id', '!=', 2)->get();
        } else{
            $this->data['homes'] = Homes::where('status_id', '!=', 2)->get();
        }

        return view('homes')->with($this->data);
    }

}
