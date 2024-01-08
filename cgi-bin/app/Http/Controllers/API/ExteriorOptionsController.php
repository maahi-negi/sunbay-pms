<?php

namespace App\Http\Controllers\API;

use App\Models\DesignCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Homes;
use App\Models\Elevations;
use App\Models\HomeDesignTypes;
use App\Models\HomeDesigns;

class ExteriorOptionsController extends Controller
{
    /**
     * @param Request $request
     * create type
     */
    public function createDesignType(Request $request){
        $destination_path = public_path('media/thumbnails');
        if($request->file('thumbnail_image'))
        {
            $thumbnail_file = $request->file('thumbnail_image');
            $thumbnail_name = $thumbnail_file->getClientOriginalName();
            $thumbnail_file->move($destination_path, $thumbnail_name);
        } else {
            $thumbnail_name = null;
        }

        if($request->file('view1_image')) {
            $view1_image_file = $request->file('view1_image');
            $ext = $view1_image_file->getClientOriginalExtension();
            $view1_image_name = time().".$ext";
        } else {
            $view1_image_name = null;
        }
        $view2_image_name = $view3_image_name = null;
        if($request->file('view2_image')) {
            $view2_image_file = $request->file('view2_image');
            $ext = $view2_image_file->getClientOriginalExtension();
            $view2_image_name = time().".$ext";
        }
        if($request->file('view3_image')) {
            $view3_image_file = $request->file('view3_image');
            $ext = $view3_image_file->getClientOriginalExtension();
            $view3_image_name = time().".$ext";
        }

        if($request->title)
        {
            $design_type = HomeDesignTypes::create([
                'title'             => $request->title,
                'slug'              => Str::slug($request->title, '-'),
                'elevation_id'      => base64_decode($request->elevation_id),
                'thumbnail'         => ($thumbnail_name)?$thumbnail_name:'',
                'image_view1'       => ($view1_image_name)?$view1_image_name:'',
                'image_view2'       => ($view2_image_name)?$view2_image_name:'',
                'image_view3'       => ($view3_image_name)?$view3_image_name:'',
                'status_id'         => $request->status,
                'priority'          => ($request->priority)?$request->priority:"1",
                'layer_option'          => ($request->layer_option)?$request->layer_option:"0"
            ]);

            $destination_path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id);
            if($request->file('view1_image')) {
                $view1_image_file->move($destination_path, $view1_image_name);
            }
            if($request->file('view2_image')) {
                $view2_image_file->move($destination_path, $view2_image_name);
            }
            if($request->file('view3_image')) {
                $view3_image_file->move($destination_path, $view3_image_name);
            }

            if($request->categories){
                $categories = json_decode($request->categories);
                foreach ($categories as $c){
                    if($c[0]!=""){
                        $category = DesignCategory::create([
                            "category" => $c[0],
                            "parent_id" => null,
                            "design_type" => $design_type->id,
                            "type" => "1"
                        ]);
                        $subCategory = (isset($c[1]))?$c[1]:[];
                        if(count($subCategory)>0 && $category){
                            foreach ($subCategory as $s){
                                if($s!=""){
                                    DesignCategory::create([
                                        "category" => $s,
                                        "parent_id" => $category->id,
                                        "design_type" => $design_type->id,
                                        "type" => "1"
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            return $design_type;
        }
    }

    public function modifyDesignType(Request $request)
    {
        $design_type = HomeDesignTypes::find($request->design_type_id);
        if($request->title)
        {
            $design_type->title = $request->title;
            $design_type->slug = Str::slug($request->title, '-');
        }
        $design_type->status_id = $request->status;
        $design_type->layer_option = $request->layer_option;
        $design_type->priority = ($request->priority)?$request->priority:"1";

        /** remove layer */
        if($request->remove_layer){
            $remove_layer = explode(",",$request->remove_layer);
            if(in_array("2",$remove_layer)){
                if($design_type->image_view2=="" && $design_type->image_view3!=""){
                    $design_type->image_view3 = "";
                }else if($design_type->image_view2 && $design_type->image_view3){
                    if(!in_array("3",$remove_layer)){
                        $design_type->image_view2 = $design_type->image_view3;
                        $design_type->image_view3 = "";
                    }
                }else{
                    $design_type->image_view2 = "";
                }
            }
            if(in_array("3",$remove_layer)){
                $design_type->image_view3 = "";
            }
        }

        $destination_path = public_path('media/thumbnails');
        if($request->file('thumbnail_image')) {
            $thumbnail_file = $request->file('thumbnail_image');
            $thumbnail_name = $thumbnail_file->getClientOriginalName();
            $thumbnail_file->move($destination_path, $thumbnail_name);
            $design_type->thumbnail = $thumbnail_name;
        }

        $destination_path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id);
        if($request->file('view1_image')) {
            $view1_image_file = $request->file('view1_image');
            $ext = $view1_image_file->getClientOriginalExtension();
            $view1_image_name = time().".$ext";
            $view1_image_file->move($destination_path, $view1_image_name);
            $design_type->image_view1 = $view1_image_name;
        }
        if($request->file('view2_image')) {
            $view2_image_file = $request->file('view2_image');
            $ext = $view2_image_file->getClientOriginalExtension();
            $view2_image_name = time().".$ext";
            $view2_image_file->move($destination_path, $view2_image_name);
            $design_type->image_view2 = $view2_image_name;
        }
        if($request->file('view3_image')) {
            $view3_image_file = $request->file('view3_image');
            $ext = $view3_image_file->getClientOriginalExtension();
            $view3_image_name = time().".$ext";
            $view3_image_file->move($destination_path, $view3_image_name);
            $design_type->image_view3 = $view3_image_name;
        }
        $design_type->save();
        if($request->categories){
            $categories_ids = DesignCategory::where(["design_type" => $design_type->id])->pluck("id")->toArray();
            $updated_cate = [];
            $categories = json_decode($request->categories);
            foreach ($categories as $c){
                if($c[0]!=""){
                    $input_data = array(
                        "category" => $c[0],
                        "parent_id" => null,
                        "design_type" => $design_type->id,
                        "type" => "1"
                    );
                    $category = DesignCategory::where($input_data)->first();
                    if($category){
                        $updated_cate[] = $category->id;
                        $category->update($input_data);
                    }else{
                        $category = DesignCategory::create($input_data);
                    }
                    $subCategory = (isset($c[1]))?$c[1]:[];
                    if(count($subCategory)>0 && $category){
                        foreach ($subCategory as $s){
                            if($s!=""){
                                $input_data = array(
                                    "category" => $s,
                                    "parent_id" => $category->id,
                                    "design_type" => $design_type->id,
                                    "type" => "1"
                                );
                                $is_category = DesignCategory::where($input_data)->first();
                                if($is_category){
                                    $updated_cate[] = $is_category->id;
                                    $is_category->update($input_data);
                                }else{
                                    DesignCategory::create($input_data);
                                }
                            }
                        }
                    }
                }
            }
            $d = $result=array_diff($categories_ids,$updated_cate);
            DesignCategory::whereIn("id",$d)->delete();
        }

        return $design_type;
    }

    public function deleteDesignType(Request $request){
        $design_type = HomeDesignTypes::find($request->design_type_id);
        $design_type->status_id = 2;
        $design_type->save();
        HomeDesigns::where('home_design_type_id',$design_type->id)->update(['status_id'=>"2"]);
    }

    /**
     * @param Request $request
     * @return mixed
     * create design
     */
    public function createDesign(Request $request){
        $design_type = HomeDesignTypes::find(base64_decode($request->design_type_id));
        $destination_path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id);
        $thumb_file = "";
        if($request->thumbnail) {
            if (str_contains($request->thumbnail, 'https://library.biorev.dev')) {
                if (@getimagesize($request->thumbnail)) {
                    $thumbnail_name = time()."-thumb.png";
                    file_put_contents($destination_path."/".$thumbnail_name, file_get_contents($request->thumbnail));

                    // resize the image
                    $filename = "$destination_path/$thumbnail_name";
                    $imgData = $this::resize_image($filename, 80, 80);
                    imagepng($imgData, $filename);
                }
            }else{
                if($request->design_type=="2"){
                    $image_parts = explode(";base64,", $request->thumbnail);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $thumbnail_name = time()."-thumb.$image_type";
                    file_put_contents($destination_path."/".$thumbnail_name, $image_base64);
                    // resize the image
                    $filename = "$destination_path/$thumbnail_name";
                    $imgData = $this::resize_image($filename, 80, 80);
                    imagepng($imgData, $filename);
                }else{
                    $thumbnail_file = $request->file('thumbnail');
                    $ext = $thumbnail_file->getClientOriginalExtension();
                    $thumbnail_name = time()."-thumb.$ext";
                    $thumbnail_file->move($destination_path, $thumbnail_name);
                }
            }

            $thumb_file = url('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id.'/'.$thumbnail_name);
        } else {
            $thumbnail_name = null;
        }

        $layer_name = "";
        if($request->design_type=="3"){
            if($request->file('image_layer')){
                $layer_file = $request->file('image_layer');
                $ext = $layer_file->getClientOriginalExtension();
                $layer_name = time()."0.$ext";
                $layer_file->move($destination_path, $layer_name);
            }
        }

        $additional_thumbs = [];
        if($request->bricks_type) {
            foreach (json_decode($request->bricks_type,true) as $i => $bt){
                if($bt){
                    if($bt=="2"){
                        $image_parts = explode(";base64,", $request["brick$i"]);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        $file_name = time()."-thumb$i.$image_type";
                        file_put_contents($destination_path."/".$file_name, $image_base64);
                    }else{
                        $thumbnail_file = $request["brick$i"];
                        $ext = $thumbnail_file->getClientOriginalExtension();
                        $file_name = time()."-thumb$i.$ext";
                        $thumbnail_file->move($destination_path, $file_name);
                    }
                    $additional_thumbs["brick$i"] = $file_name;
                }
            }
            $additional_thumbs["thumb_pattern"] = $request->brick_pattern;
        }
        if($request->title) {
            $color_RBG = "";
            if($request->colorR && $request->colorG && $request->colorB){
                $color_RBG = "$request->colorR,$request->colorG,$request->colorB";
            }
            $design = HomeDesigns::create([
                'title'             => $request->title,
                'slug'              => Str::slug($request->title, '-'),
                'elevation_id'      => base64_decode($request->elevation_id),
                'home_design_type_id' => base64_decode($request->design_type_id),
                'product_id'        => ($request->product_id)?$request->product_id:"",
                'status_id'         => $request->status,
                'design_type'       => $request->design_type,
                'rgb_color'         => $color_RBG,
                'hex_color'         => ($request->hex_color)?$request->hex_color:"",
                'thumbnail'         => ($thumbnail_name)?$thumbnail_name:'',
                'layer'             => ($layer_name)?$layer_name:'',
                'additional_thumbs' => json_encode($additional_thumbs),
                'category'          => ($request->category)?$request->category:null,
                'sub_category'      => ($request->category && $request->sub_category)?$request->sub_category:null,
            ]);

            if($design && $request->add_to_library=="1"){
                $type = str_replace(" ","",$design_type->title);
                $this->saveColorData($design,$thumb_file,$type);
            }
        }
        return $design;
    }

    public function modifyDesign(Request $request)
    {
        $design = HomeDesigns::find($request->design_id);
        if($request->title) {
            $design->title = $request->title;
            $design->slug = Str::slug($request->title, '-');
        }
        $design->category = "";
        $design->sub_category = "";
        if($request->category) {
            $design->category = $request->category;

            if($request->sub_category) {
                $design->sub_category = $request->sub_category;
            }
        }
        $design_type = HomeDesignTypes::find(base64_decode($request->design_type_id));
        $destination_path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id);

        if($request->design_type=="1"){
            if($request->product_id) {
                $design->product_id = $request->product_id;
            }
            $design->rgb_color = "";
            if($request->colorR!="" && $request->colorG!="" && $request->colorB!="") {
                $design->rgb_color = "$request->colorR,$request->colorG,$request->colorB";
            }
            if($request->hex_color) {
                $design->hex_color = $request->hex_color;
            }
        }else{
            $design->product_id = "";
            $design->rgb_color = "";
            $design->hex_color = "";
            $additional_thumbs = ($design->additional_thumbs)?json_decode($design->additional_thumbs,true):[];
            if($request->bricks_type){
                foreach (json_decode($request->bricks_type,true) as $i => $bt){
                    if($bt){
                        if($bt=="2"){
                            $image_parts = explode(";base64,", $request["brick$i"]);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);
                            $file_name = time()."-thumb$i.$image_type";
                            file_put_contents($destination_path."/".$file_name, $image_base64);
                        }else{
                            $thumbnail_file = $request["brick$i"];
                            $ext = $thumbnail_file->getClientOriginalExtension();
                            $file_name = time()."-thumb$i.$ext";
                            $thumbnail_file->move($destination_path, $file_name);
                        }
                        $additional_thumbs["brick$i"] = $file_name;
                    }
                }
                $additional_thumbs["thumb_pattern"] = $request->brick_pattern;
            }
            $design->additional_thumbs = json_encode($additional_thumbs);
        }

        if($request->thumbnail) {
            if($request->design_type=="2"){
                $image_parts = explode(";base64,", $request->thumbnail);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $thumbnail_name = time()."-thumb.$image_type";
                file_put_contents($destination_path."/".$thumbnail_name, $image_base64);
                $design->thumbnail = $thumbnail_name;

                // resize the image
                $filename = "$destination_path/$thumbnail_name";
                $imgData = $this::resize_image($filename, 80, 80);
                imagepng($imgData, $filename);
            }else{
                $thumbnail_file = $request->file('thumbnail');
                $ext = $thumbnail_file->getClientOriginalExtension();
                $thumbnail_name = time()."-thumb.$ext";
                $thumbnail_file->move($destination_path, $thumbnail_name);
                $design->thumbnail = $thumbnail_name;
            }
        }

        if($request->design_type=="3"){
            if($request->file('image_layer')){
                $layer_file = $request->file('image_layer');
                $ext = $layer_file->getClientOriginalExtension();
                $layer_name = time()."0.$ext";
                $layer_file->move($destination_path, $layer_name);
                $design->layer = $layer_name;
            }
        }

        $design->status_id = $request->status;
        $design->design_type = $request->design_type;
        $design->save();
        return $design;
    }

    public function deleteDesign(Request $request)
    {
        $design = HomeDesigns::find($request->design_id);
        if($design->is_default == 1){
            return response()->json('Cannot delete default design.', 422);
        }
        $design->status_id = 2;
        $design->save();
        return response()->json('success', 200);
    }

    public function updateDefault(Request $request)
    {
        $design = HomeDesigns::find($request->design_id);
        if($design->status_id != 1)
        {
            return response()->json('Design is not active.', 422);
        }
        HomeDesigns::where('home_design_type_id', $design->home_design_type_id)->update(['is_default' => 0]);
        $design->is_default = 1;
        $design->save();
    }

    public function tempImages()
    {
        $images = [];
        $images1 = HomeDesigns::where('status_id', 1)->with('design_type')->get()->toArray();
        foreach ($images1 as $key => $value) {
            if($value['image_view1']) array_push($images, '/media/uploads/'.$value['design_type']['title'].'/'.$value['image_view1']);
            if($value['image_view2']) array_push($images, '/media/uploads/'.$value['design_type']['title'].'/'.$value['image_view2']);
            if($value['open_view_image']) array_push($images, '/media/uploads/'.$value['design_type']['title'].'/'.$value['open_view_image']);
            if($value['open_view2_image']) array_push($images, '/media/uploads/'.$value['design_type']['title'].'/'.$value['open_view2_image']);
        }
        $images = array_values(array_filter($images));
        return $images;
    }

    /**
     * resize image
     */
    private function resize_image($file, $w, $h, $crop=false) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }

        //Get file extension
        $imgInfo = getimagesize($file);
        $mime = $imgInfo['mime'];
        switch($mime){
            case 'image/jpeg':
                $src = imagecreatefromjpeg($file);
                break;
            case 'image/png':
                $src = imagecreatefrompng($file);
                break;
            case 'image/gif':
                $src = imagecreatefromgif($file);
                break;
            default:
                $src = imagecreatefromjpeg($file);
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    public function saveColorData($data,$texture,$type){
        $body = json_encode(array(
            'color_name' => $data->title,
            'sw_code' => $data->product_id,
            'rgb_code' => $data->rgb_color,
            'hex_code' => $data->hex_color,
            'type' => $type,
            'texture' => $texture,
        ));
        //$data = http_build_query($data);
        $token = "LcMDq4lDWA3Zl8hN45l4xFqMe5jJnLprr17iyLq9DNme!MOa2maMY6F7Yx8B7Ouz";
        $header = Array("Content-Type: application/json","token: $token");
        $curl = curl_init("https://library.biorev.dev/api/submit-color-data");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $response = json_decode(curl_exec($curl),true);
        curl_close($curl);
        return true;
    }

    /**
     * @param $id
     * @return array
     * get design category
     */
    public function getDesignCategory($id){
        $design_type = HomeDesignTypes::find($id);
        $design_categories = [];
        if($design_type){
            $categories = DesignCategory::where(['design_type'=>$id,"type"=>"1"])->get();
            if(count($categories)>0){
                foreach ($categories as $cate){
                    if(!$cate->parent_id){
                        $design_categories[$cate->id]["category"] = $cate->category;
                    }else{
                        $design_categories[$cate->parent_id]["sub_category"][] = $cate->category;
                    }
                }
            }
        }
        return $design_categories;
    }

    /**
     * @param Request $request
     * @return string
     * get design sub category
     */
    public function getDesignSubCategory(Request $request){
        $categories = DesignCategory::where(["parent_id" => $request->category_id])->pluck("category","id")->toArray();
        $selected_sub_cate = "";
        $response = "";
        if($request->designId && $request->sub_category){
            $selected_sub_cate = $request->sub_category;
        }
        if(count($categories)>0){
            $response .= "<option value=''>Select Sub Category</option>";
            foreach ($categories as $k => $c){
                $is_selected = ($selected_sub_cate == $k)?"selected":"";
                $response .= "<option value='$k' $is_selected>$c</option>";
            }
        }
        return $response;
    }

    /**
     * add design option
     */
    public function addDesignOption(Request $request){
        $design_type = HomeDesignTypes::find($request->design_type_id);
        if($design_type){
            $destination_path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id);
            $thumb_file = "";
            if($request->thumbnail) {
                if (str_contains($request->thumbnail, 'https://library.biorev.dev')) {
                    if (@getimagesize($request->thumbnail)) {
                        $thumbnail_name = time()."-thumb.png";
                        file_put_contents($destination_path."/".$thumbnail_name, file_get_contents($request->thumbnail));

                        // resize the image
                        $filename = "$destination_path/$thumbnail_name";
                        $imgData = $this::resize_image($filename, 80, 80);
                        imagepng($imgData, $filename);
                    }
                }else{
                    if($request->design_type=="2"){
                        $image_parts = explode(";base64,", $request->thumbnail);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        $thumbnail_name = time()."-thumb.$image_type";
                        file_put_contents($destination_path."/".$thumbnail_name, $image_base64);
                        // resize the image
                        $filename = "$destination_path/$thumbnail_name";
                        $imgData = $this::resize_image($filename, 80, 80);
                        imagepng($imgData, $filename);
                    }else{
                        $thumbnail_file = $request->file('thumbnail');
                        $ext = $thumbnail_file->getClientOriginalExtension();
                        $thumbnail_name = time()."-thumb.$ext";
                        $thumbnail_file->move($destination_path, $thumbnail_name);
                    }
                }

                $thumb_file = url('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id.'/'.$thumbnail_name);
            } else {
                $thumbnail_name = null;
            }

            $layer_name = "";
            if($request->design_type=="3"){
                if($request->file('image_layer')){
                    $layer_file = $request->file('image_layer');
                    $ext = $layer_file->getClientOriginalExtension();
                    $layer_name = time()."0.$ext";
                    $layer_file->move($destination_path, $layer_name);
                }
            }

            $additional_thumbs = [];
            if($request->bricks_type) {
                foreach (json_decode($request->bricks_type,true) as $i => $bt){
                    if($bt){
                        if($bt=="2"){
                            $image_parts = explode(";base64,", $request["brick$i"]);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);
                            $file_name = time()."-thumb$i.$image_type";
                            file_put_contents($destination_path."/".$file_name, $image_base64);
                        }else{
                            $thumbnail_file = $request["brick$i"];
                            $ext = $thumbnail_file->getClientOriginalExtension();
                            $file_name = time()."-thumb$i.$ext";
                            $thumbnail_file->move($destination_path, $file_name);
                        }
                        $additional_thumbs["brick$i"] = $file_name;
                    }
                }
                $additional_thumbs["thumb_pattern"] = $request->brick_pattern;
            }
            if($request->title) {
                $color_RBG = "";
                if($request->colorR && $request->colorG && $request->colorB){
                    $color_RBG = "$request->colorR,$request->colorG,$request->colorB";
                }
                $design = HomeDesigns::create([
                    'title'             => $request->title,
                    'slug'              => Str::slug($request->title, '-'),
                    'elevation_id'      => $design_type->elevation_id,
                    'home_design_type_id' => $request->design_type_id,
                    'product_id'        => ($request->product_id)?$request->product_id:"",
                    'status_id'         => $request->status,
                    'design_type'       => $request->design_type,
                    'rgb_color'         => $color_RBG,
                    'hex_color'         => ($request->hex_color)?$request->hex_color:"",
                    'thumbnail'         => ($thumbnail_name)?$thumbnail_name:'',
                    'layer'             => ($layer_name)?$layer_name:'',
                    'additional_thumbs' => json_encode($additional_thumbs),
                    'category'          => ($request->category)?$request->category:null,
                    'sub_category'      => ($request->category && $request->sub_category)?$request->sub_category:null,
                ]);

                $designpath = 'media/uploads/exterior/';
                $design_group = Homes::find($design_type->elevation_id);
                $arr = explode(" ",$design->title);
                array_splice($arr,0,1);
                $newStr = implode(" ",$arr);
                $additional_pattern = "";
                if($design->additional_thumbs){
                    $additional_thumbs = json_decode($design->additional_thumbs,true);
                    $additional_pattern = @$additional_thumbs['thumb_pattern'];
                }
                if($design->thumbnail){
                    $thumbnail = asset($designpath.$design_type->slug.'_'.$design_type->id.'/'.$design->thumbnail);
                    $background = "url('$thumbnail')";
                }else{
                    $background = "rgb($design->rgb_color)";
                }
                if($design->design_type=="3" && $design->layer){
                    $image_view1 = ($design->layer)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design->layer):"";
                    $image_view2 = "";
                    $image_view3 = "";
                }else{
                    $image_view1 = ($design_type->image_view1)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view1):"";
                    $image_view2 = ($design_type->image_view2)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view2):"";
                    $image_view3 = ($design_type->image_view3)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design_type->image_view3):"";
                }

                $baseImg1 = ($design_group->base_image_view1)?asset($designpath.$design_group->base_image_view1):"";
                $baseImg2 = ($design_group->base_image_view2)?asset($designpath.$design_group->base_image_view2):"";
                $dataTexture = ($design->thumbnail)?asset($designpath.$design_type->slug."_".$design_type->id."/".$design->thumbnail):"";
                $dtitle = str_replace('.', '', explode(' ',$design->title)[0]);
                return response()->json([
                    'status'=>1,
                    "design_type"=>$design_type,
                    "design"=>$design,
                    "background"=>$background,
                    "baseImg1"=>$baseImg1,
                    "baseImg2"=>$baseImg2,
                    "image_view1"=>$image_view1,
                    "image_view2"=>$image_view2,
                    "image_view3"=>$image_view3,
                    "dataTexture"=>$dataTexture,
                    "additional_pattern"=>$additional_pattern,
                    "dtitle"=>$dtitle,
                    "newStr"=>$newStr,
                ],200);
            }
        }
        return response()->json(['status'=>0,'msg'=>'Data not saved. Please try again'],500);
    }
}
