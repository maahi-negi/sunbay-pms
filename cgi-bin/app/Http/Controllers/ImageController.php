<?php

namespace App\Http\Controllers;

use App\Models\DesignCategory;
use App\Models\HomeDesigns;
use App\Models\HomeDesignTypes;
use http\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('uploads/tmp');

        return response()->json(['message' => 'Image uploaded successfully', 'imagePath' => $imagePath]);
    }

    public function uploadFromUrl(Request $request)
    {
        $request->validate([
            'imageUrl' => 'required|url',
        ]);

        $imageUrl = $request->input('imageUrl');
        $imageUrl = explode('?', $imageUrl)[0];
        $newPath = '/home/xhome/public_html/ule-uat.xhome360.com/uploads/tmp/' . trim(basename($imageUrl));;
        //dd($basePath);
        $imageData = file_get_contents($imageUrl);
        if ($imageData !== false) {
            $imagePath = public_path('uploads/tmp/'. trim(basename($imageUrl)));
//            $imagePath = '/uploads/tmp/' . trim(basename($imageUrl));
            try {
                file_put_contents($imagePath,$imageData);
//                Storage::put($imagePath, $imageData);
//                rename('/home/xhome/public_html/ule-uat.xhome360.com/cgi-bin/storage/app'.$imagePath, $newPath);

            } catch (\Exception $e) {
                echo 'Error saving image: ' . $e->getMessage();
            }

            return response()->json(['message' => 'Image from URL uploaded successfully', 'imagePath' =>  asset('/uploads/tmp/' . basename($imageUrl))]);
        } else {
            return response()->json(['error' => 'Error downloading image from URL'], 500);
        }
    }

    public function uploadFromFile(Request $request) {
        // Validate the uploaded file
        /*dd($request->file('image_file'));
        $request->validate([
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);*/

        $user = auth()->user();

        // Get user ID
        //$user_id = $user->id;
        //dd($user);
        $user_id = 0;
        if($user){
            $user_id = $user->id;


        }

        // Store the uploaded file
        $image = $request->file('image_file');
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('uploads'), $imageName);

        // You may want to save the image path in the database or perform other actions

        return response()->json(['message' => 'Image from URL uploaded successfully ', 'imagePath' =>  asset('/uploads/' . basename($imageName))]);
    }


    public function createDesign(Request $request){
        $design_type = HomeDesignTypes::find($request->design_type_id);
        $destination_path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id);

        $thumb_file = "";
        $user_id = 0;
        $user = auth()->user();
        if(!$user){
            return response()->json(['error' => 'User not found'], 404);
        }
        $user_id = $user->id;

        if($request->file('image_file_pr')) {

            $file = $request->file('image_file_pr');

            // Generate a unique filename for the file
            $thumbnail_name = time() . '-thumb.' . $file->clientExtension();

            // Store the file in the 'uploads' disk (configured in config/filesystems.php)
            //$path = $file->storeAs('uploads', $thumbnail_name, '');
            $file->move($destination_path, $thumbnail_name);


            $filename = "$destination_path/$thumbnail_name";
            $imgData = $this::resize_image($filename, 80, 80);
            imagepng($imgData, $filename);

        } else if($request->imageUrl) {

            $image_parts = explode(";base64,", $request->imageUrl);
            $image_type_aux = explode("image/", $image_parts[0]);
            if(isset($image_type_aux[1])) {
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $thumbnail_name = time() . "-thumb.$image_type";
                file_put_contents($destination_path . "/" . $thumbnail_name, $image_base64);
            } else {
                $thumbnail_name = time() . "-thumb.png";
                file_put_contents($destination_path . "/" . $thumbnail_name, file_get_contents($request->imageUrl));
            }
            // resize the image
            $filename = "$destination_path/$thumbnail_name";
            $imgData = $this::resize_image($filename, 80, 80);
            imagepng($imgData, $filename);



            $thumb_file = url('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id.'/'.$thumbnail_name);
        } else {
            $thumbnail_name = null;
        }

        $layer_name = "";

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
                'user_id'           => $user_id,
                'title'             => $request->title,
                'slug'              => Str::slug($request->title, '-'),
                'elevation_id'      => $request->elevation_id,
                'home_design_type_id' => $request->design_type_id,
                'product_id'        => "",
                'status_id'         => $request->status,
                'design_type'       => $request->design_type,
                'rgb_color'         => "",
                'hex_color'         => "",
                'thumbnail'         => ($thumbnail_name)?$thumbnail_name:'',
                'layer'             => '',
                'additional_thumbs' => json_encode($additional_thumbs),
                'category'          => null,
                'sub_category'      => null,
            ]);
        }
        return $design;
    }


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
}
