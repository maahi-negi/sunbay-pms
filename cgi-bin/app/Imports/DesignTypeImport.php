<?php

namespace App\Imports;

use App\Models\HomeDesignTypes;
use App\Models\Homes;
use App\Models\History;
use App\Models\ErrorHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
// setting the default heading structure to none
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');

class DesignTypeImport implements ToModel, WithHeadingRow,WithValidation,SkipsOnFailure
{
    use Importable;
    public $mapChoice;
    public $rules = [];
    public $imported_on;
    public $flag;

    public function __construct($mapChoice,$importing_on,$flag)
    {
        # code...
        $this->mapChoice = (array)$mapChoice;
        $this->imported_on = $importing_on;
        $this->flag = $flag;
        $this->mapChoice = array_flip($this->mapChoice);
        if(array_key_exists('elevation_id',$this->mapChoice))
        {
            $this->rules[$this->mapChoice['elevation_id']] = 'required';
        }
        if(array_key_exists('title',$this->mapChoice))
        {
            $this->rules[$this->mapChoice['title']] = 'required';
        }
    }

    public function model(array $row)
    {
        //continue or stop
        if(!array_key_exists('elevation_id',$this->mapChoice)){
            // there is no field map with community name. Terminate the whole process and exit
            return;
        }
        foreach($this->mapChoice as $key => $value)
        {
            $c_data[$key] =  $row[$this->mapChoice[$key]];
        }
        $home_slug  = str_replace(' ', '-', strtolower($row[$this->mapChoice['elevation_id']]));
        $_slug  = str_replace(' ', '-', strtolower($row[$this->mapChoice['title']]));
        $home = Homes::where('slug', $home_slug)->get(['id', 'slug'])->first();
        if(!$home) {
            $data = serialize($c_data);
            ErrorHistory::create([
                'data'          => $data,
                'type'          => 'design_type',
                'flag'          => 'skip',
                'imported_on'   => $this->imported_on,
                'msg'           => 'Home found in sheet do not exist'
            ]);
            return;
        }
        if(HomeDesignTypes::where('title', 'like', $row[$this->mapChoice['title']])->where('elevation_id', $home->id)->count() == 0)
        {
            $c_data['status_id'] = 1;
            $c_data['slug'] = $_slug;
            $c_data['elevation_id'] = $home->id;
            $c_data['imported_on'] = $this->imported_on;

            if(@$row[$this->mapChoice['thumbnail']]){
                $img = time().'thumb.png';
                $path = public_path('media/thumbnails/'.$img);
                $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['thumbnail']],$path);
                if ($thumbnail) {
                    $c_data['thumbnail'] = $img;
                }
            }
            $design_type = HomeDesignTypes::create($c_data);
            if($design_type){
                $destination_path = public_path('media/uploads/exterior/'.$_slug.'_'.$design_type->id);
                if(!file_exists($destination_path)){
                mkdir($destination_path, 0777, true);
                }
                if(isset($this->mapChoice['image_view1'],$row[$this->mapChoice['image_view1']]) && $row[$this->mapChoice['image_view1']]){
                    $img = time().'1.png';
                    $path = $destination_path."/".$img;
                    $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['image_view1']],$path);
                    if ($thumbnail) {
                        $design_type->update(['image_view1'=>$img]);
                    }
                }
                if(isset($this->mapChoice['image_view2'],$row[$this->mapChoice['image_view2']]) && $row[$this->mapChoice['image_view2']]){
                    $img = time().'2.png';
                    $path = $destination_path."/".$img;
                    $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['image_view2']],$path);
                    if ($thumbnail) {
                        $design_type->update(['image_view2'=>$img]);
                    }
                }
                if(isset($this->mapChoice['image_view3'],$row[$this->mapChoice['image_view3']]) && $row[$this->mapChoice['image_view3']]){
                    $img = time().'3.png';
                    $path = $destination_path."/".$img;
                    $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['image_view2']],$path);
                    if ($thumbnail) {
                        $design_type->update(['image_view2'=>$img]);
                    }
                }
            }
            return;
        }
        elseif(HomeDesignTypes::where('title', 'like', $row[$this->mapChoice['title']])->where('elevation_id', $home->id)->where("status_id","!=","2")->count() != 0 && $this->flag == 'skip'){
            $data = serialize($c_data);
            ErrorHistory::create([
                'data'          => $data,
                'type'          => 'design_type',
                'flag'          => 'skip',
                'imported_on'   => $this->imported_on
            ]);
            return null;
        }
        elseif(HomeDesignTypes::where('title', 'like', $row[$this->mapChoice['title']])->where('elevation_id', $home->id)->count() != 0 && ($this->flag == 'update' || $this->flag == 'skip')){
            $design_type = HomeDesignTypes::where('title', 'like', $row[$this->mapChoice['title']])->where('elevation_id', $home->id)->first();
            $c_data['elevation_id'] = $home->id;
            $c_data['imported_on'] = $this->imported_on;
            $c_data['status_id'] = 1;
            $c_data['slug'] = $_slug;
            $c_data['thumbnail'] = $design_type->thumbnail;
            if(@$row[$this->mapChoice['thumbnail']]){
                $img = time().'thumb.png';
                $path = public_path('media/thumbnails/'.$img);
                $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['thumbnail']],$path);
                if ($thumbnail) {
                    $exist_file = public_path('media/thumbnails/'.$design_type->thumbnail);
                    if(file_exists($exist_file)){
                        unlink($exist_file);
                    }
                    $c_data['thumbnail'] = $img;
                }
            }

            $destination_path = public_path('media/uploads/exterior/'.$_slug.'_'.$design_type->id);
            $c_data['image_view1'] = $design_type->image_view1;
            $c_data['image_view2'] = $design_type->image_view2;
            $c_data['image_view3'] = $design_type->image_view3;
            if(isset($this->mapChoice['image_view1'],$row[$this->mapChoice['image_view1']]) && $row[$this->mapChoice['image_view1']]){
                $img = time().'1.png';
                $path = $destination_path."/".$img;
                $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['image_view1']],$path);
                if ($thumbnail) {
                    $exist_file = $destination_path.'/'.$design_type->image_view1;
                    if(file_exists($exist_file)){
                        unlink($exist_file);
                    }
                    $c_data['image_view1'] = $img;
                }
            }
            if(isset($this->mapChoice['image_view2'],$row[$this->mapChoice['image_view2']]) && $row[$this->mapChoice['image_view2']]){
                $img = time().'2.png';
                $path = $destination_path."/".$img;
                $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['image_view2']],$path);
                if ($thumbnail) {
                    $exist_file = $destination_path.'/'.$design_type->image_view2;
                    if(file_exists($exist_file)){
                        unlink($exist_file);
                    }
                    $c_data['image_view2'] = $img;
                }
            }
            if(isset($this->mapChoice['image_view3'],$row[$this->mapChoice['image_view3']]) && $row[$this->mapChoice['image_view3']]){
                $img = time().'3.png';
                $path = $destination_path."/".$img;
                $thumbnail = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['image_view3']],$path);
                if ($thumbnail) {
                    $exist_file = $destination_path.'/'.$design_type->image_view3;
                    if(file_exists($exist_file)){
                        unlink($exist_file);
                    }
                    $c_data['image_view3'] = $img;
                }
            }

            HomeDesignTypes::where('title', 'like', $row[$this->mapChoice['title']])->where('elevation_id', $home->id)->update($c_data);
        }
        else{
            return;
        }
    }
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
        $err = serialize($failures);
        ErrorHistory::create([
            'data'          => $err,
            'type'          =>'design_type',
            'imported_on'   => $this->imported_on
        ]);
    }
}
