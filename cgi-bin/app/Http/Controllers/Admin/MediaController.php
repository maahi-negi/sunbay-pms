<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public $data;
    public $files = array();

    private function check_files_details($design,$destination_path,$field,$media_array){
        $files_array = $media_array['files'];
        $file_size = $media_array['file_size'];

        foreach ($design as $row){
            $file = "$destination_path/".$row["$field"];
            if(!in_array($file,$files_array)){
                if(file_exists(public_path("$file"))){
                    $files_array[] = $file;
                    $file_size += filesize(public_path("$file"));
                }
            }
        }
        $media_array = array("files" => $files_array,"file_size"=>$file_size);
        return $media_array;
    }

    public function media_details(){
        $media_array = array("files" => array(),"file_size"=>0);
        $design_type = \App\Models\DesignTypes::select("thumbnail")->where("status_id","!=","2")->get();
        if(count($design_type)>0){
            $destination_path = "media";
            $media_array =  $this::check_files_details($design_type,$destination_path,"thumbnail",$media_array);
        }

        $design_type = \App\Models\HomeDesignTypes::select("thumbnail")->where("status_id","!=","2")->get();
        if(count($design_type)>0){
            $destination_path = "media/thumbnails";
            $media_array =  $this::check_files_details($design_type,$destination_path,"thumbnail",$media_array);
        }

        $design_group = \App\Models\DesignGroups::select("base_image_view1")->where("status_id","!=","2")->get();
        if(count($design_group)>0){
            $destination_path = "media/uploads";
            $media_array =  $this::check_files_details($design_group,$destination_path,"base_image_view1",$media_array);
        }

        $homeplan = \App\Models\Homes::select("base_image")->where("status_id","!=","2")->get();
        if(count($homeplan)>0){
            $destination_path = "media/uploads/exterior";
            $media_array =  $this::check_files_details($homeplan,$destination_path,"base_image",$media_array);
        }

        $elevation = \App\Models\Elevations::select("base_image")->where("status_id","!=","2")->get();
        if(count($elevation)>0){
            $destination_path = "media/uploads/exterior";
            $media_array =  $this::check_files_details($elevation,$destination_path,"base_image",$media_array);
        }

        $floorplan = \App\Models\HomeFloorplans::select("image")->where("status_id","!=","2")->get();
        if(count($floorplan)>0){
            $destination_path = "media/uploads/floorplan/base-image";
            $media_array =  $this::check_files_details($floorplan,$destination_path,"image",$media_array);
        }
        $feature = \App\Models\HomeFloorFeatures::select("image_view1")->where("status_id","!=","2")->get();
        if(count($feature)>0){
            $destination_path = "media/uploads/features";
            $media_array =  $this::check_files_details($feature,$destination_path,"image_view1",$media_array);
        }

        $design = \App\Models\Designs::where("status_id","!=","2")->orderBy("design_type_id","asc")->get();
        $design_type_id = 0;
        if(count($design)>0){
            $destination_path = "";
            $files_array = $media_array['files'];
            $file_size = $media_array['file_size'];
            foreach ($design as $row){
                if($design_type_id!=$row->design_type_id){
                    $design_type_id = $row->design_type_id;
                    $design_type = \App\Models\DesignTypes::where('id',$row->design_type_id)->first();
                    $destination_path = "media/uploads/".$design_type->slug.'_'.$design_type->id;
                }
                $file = "$destination_path/$row->thumbnail";
                if(!in_array($file,$files_array)){
                    if(file_exists(public_path("$file"))){
                        $files_array[] = $file;
                        $file_size += filesize(public_path("$file"));
                    }
                }
                $file = "$destination_path/$row->image_view1";
                if(!in_array($file,$files_array)){
                    if(file_exists(public_path("$file"))){
                        $files_array[] = $file;
                        $file_size += filesize(public_path("$file"));
                    }
                }
            }
            $media_array = array("files" => $files_array,"file_size"=>$file_size);
        }

        $design = \App\Models\HomeDesigns::where("status_id","!=","2")->orderBy("home_design_type_id","asc")->get();
        $design_type_id = 0;
        if(count($design)>0){
            $destination_path = "";
            $files_array = $media_array['files'];
            $file_size = $media_array['file_size'];
            foreach ($design as $row){
                if($design_type_id!=$row->home_design_type_id){
                    $design_type_id = $row->home_design_type_id;
                    $design_type = \App\Models\HomeDesignTypes::where('id',$row->home_design_type_id)->first();
                    $destination_path = "media/uploads/exterior/".$design_type->slug.'_'.$design_type->id;
                }
                $file = "$destination_path/$row->thumbnail";
                if(!in_array($file,$files_array)){
                    if(file_exists(public_path("$file"))){
                        $files_array[] = $file;
                        $file_size += filesize(public_path("$file"));
                    }
                }
                $file = "$destination_path/$row->image_view1";
                if(!in_array($file,$files_array)){
                    if(file_exists(public_path("$file"))){
                        $files_array[] = $file;
                        $file_size += filesize(public_path("$file"));
                    }
                }
            }
            $media_array = array("files" => $files_array,"file_size"=>$file_size);
        }

        $media_array["file_size"] = round($media_array["file_size"] / 1024 / 1024, 2);
        
        $this->data['used_files'] = $media_array['files'];
        $this->data['used_files_size'] = $media_array['file_size'];
    }

    private function listFolderFiles($dir){
        $ffs = scandir(public_path($dir));
        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);
        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        foreach($ffs as $ff){
            if(is_dir($dir.'/'.$ff)){
                $this::listFolderFiles($dir.'/'.$ff);
            }else{
                $this->files[$dir][] = $ff;
            }

        }
    }

    /**
     * get used and unused media files
     */
    public function getAllMediaFiles(){
        $this->data['menu'] = 'media';
        $this::media_details();

        $dir = 'media';
        $this::listFolderFiles($dir);
        $files_array = [];
        $file_size = 0;

        foreach ($this->files as $folder => $ff){
            foreach ($ff as $file){
                $file_path = public_path("$folder/$file");
                $files_array[] = "$folder/$file";
                $file_size += filesize($file_path);
            }
        }
        $media_size = round($file_size / 1024 / 1024, 2);
        $this->data['media_files'] = $files_array;
        $this->data['media_files_size'] = $media_size;
        $this->data['unused_file_size'] = $media_size - $this->data['used_files_size'];
        return view('admin.media')->with($this->data);
    }

    /**
     * @param Request $request
     * remove media
     */
    public function remove_media(Request $request){
        $file = public_path($request->file_name);
        if(File::exists($file)){
            $file_size = filesize($file);
            $file_size = round($file_size / 1024 / 1024, 2);
//            File::delete($file);
            return $file_size;
        }
        return 0;
    }

}
