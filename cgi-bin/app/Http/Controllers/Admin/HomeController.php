<?php

namespace App\Http\Controllers\Admin;

use App\Models\ColorLibrary;
use App\Models\PatternLibrary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Homes;
use App\Models\Elevations;
use App\Models\HomeDesignTypes;
use App\Models\HomeDesigns;
use App\Models\DesignCategory;

class HomeController extends Controller
{
    public $data;
    protected $token="LcMDq4lDWA3Zl8hN45l4xFqMe5jJnLprr17iyLq9DNme!MOa2maMY6F7Yx8B7Ouz";

    public function homeplans()
    {
        $this->data['menu'] = 'homeplans';
        $this->data['homes'] = Homes::where('status_id', '!=', 2)->get();
        return view('admin.homeplans')->with($this->data);
    }

    public function exterior()
    {
        $this->data['menu'] = 'exterior';
        $this->data['homes'] = Homes::with('extelevations')->where('status_id', '!=', 2)->where('exterior', 1)->get();
        return view('admin.exterior')->with($this->data);
    }

    public function elevations($home_id)
    {
        $this->data['menu'] = 'homeplans';
        $this->data['elevations'] = Elevations::where('home_id', base64_decode($home_id))->where('status_id', '!=', 2)->get();
        $this->data['home_id'] = $home_id;
        $this->data['home_title'] = Homes::where('id', base64_decode($home_id))->first()->title;
        $this->data['home'] = Homes::where('id', base64_decode($home_id))->first();
        return view('admin.elevations')->with($this->data);
    }

    public function designTypes($elevation_id)
    {
        $this->data['menu'] = 'homeplans';
        $this->data['design_types'] = HomeDesignTypes::where('elevation_id', base64_decode($elevation_id))->where('status_id', '!=', 2)->orderBy("priority","asc")->get();
        $this->data['elevation_id'] = $elevation_id;
        $this->data['home_title'] = Homes::where('id', base64_decode($elevation_id))->first()->title;
        return view('admin.home-design-types')->with($this->data);
    }

    public function designs($home_design_type_id, $elevation_id){
        $this->data['menu'] = 'homeplans';
        $this->data['designs'] = HomeDesigns::where('home_design_type_id', base64_decode($home_design_type_id))->where('status_id', '!=', 2)->get();
        $this->data['design_type_id'] = $home_design_type_id;
        $design_type = HomeDesignTypes::where('id',base64_decode($home_design_type_id))->get(['slug', 'title', 'id','image_view1','image_view2','image_view3','layer_option'])->first();
        $this->data['design_type_slug'] = $design_type->slug.'_'.$design_type->id;
        $this->data['design_type_title'] = $design_type->title;
        $this->data['design_type'] = $design_type;

        $this->data['elevation_id'] = $elevation_id;
        $this->data['home_title'] = Homes::where('id', base64_decode($elevation_id))->first()->title;
        $this->data['design_type_categories'] = DesignCategory::where(['design_type'=>$design_type->id,"type"=>"1"])->where("parent_id",null)->pluck("category","id")->toArray();
        return view('admin.home-designs')->with($this->data);
    }

    public function getColor(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://library.biorev.dev/api/get-data');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json","token: $this->token"
        ));
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return [];
        } else {
            return json_decode($response,true);
        }
    }

    public function colorlibrary()
    {
        $this->data['menu'] = 'colorlibrary';
        $this->data['colors'] = ColorLibrary::where('status_id', '!=', 2)->get();
        return view('admin.colorlibrary')->with($this->data);
    }
    public function patternlibrary()
    {
        $this->data['menu'] = 'patternlibrary';
        $this->data['patterns'] = PatternLibrary::where('status_id', '!=', 2)->get();
        return view('admin.patternlibrary')->with($this->data);
    }
}
