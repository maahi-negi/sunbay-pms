<?php

namespace App\Imports;

use App\Models\HomeDesigns;
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
class DesignOptionImport implements ToModel, WithHeadingRow,WithValidation,SkipsOnFailure
{
    use Importable;
    public $mapChoice;
    public $rules = [];
    public $imported_on;
    public $parent_id;
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
        if(array_key_exists('home_design_type_id',$this->mapChoice))
        {
            $this->rules[$this->mapChoice['home_design_type_id']] = 'required';
        }
        if(array_key_exists('title',$this->mapChoice))
        {
            $this->rules[$this->mapChoice['title']] = 'required';
        }
    }

    public function model(array $row)
    {
        //continue or stop
        if(!array_key_exists('elevation_id',$this->mapChoice) && !array_key_exists('home_design_type_id',$this->mapChoice)){
            // there is no field map with  name. Terminate the whole process and exit
            return;
        }
        foreach($this->mapChoice as $key => $value) {
            $c_data[$key] =  $row[$this->mapChoice[$key]];
        }

        $home_slug  = str_replace(' ', '-', strtolower($row[$this->mapChoice['elevation_id']]));
        $_slug  = str_replace(' ', '-', strtolower($row[$this->mapChoice['title']]));
        $home       = Homes::where('slug', $home_slug)->get(['id', 'slug'])->first();
        if(!$home) {
            $data = serialize($c_data);
            ErrorHistory::create([
                'data'          => $data,
                'type'          => 'design_options',
                'flag'          => 'skip',
                'imported_on'   => $this->imported_on,
                'msg'           => 'Home or Design type found in sheet do not exist'
            ]);
            return;
        }
        $design_type = HomeDesignTypes::where('title', 'like', $row[$this->mapChoice['home_design_type_id']])->where('elevation_id', $home->id)->first();
        if(!$design_type) {
            $data = serialize($c_data);
            ErrorHistory::create([
                'data'          => $data,
                'type'          => 'design_options',
                'flag'          => 'skip',
                'imported_on'   => $this->imported_on,
                'msg'           => 'Home or Design type found in sheet do not exist'
            ]);
            return;
        }

        if(HomeDesigns::where('title', 'like', $row[$this->mapChoice['title']])->where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->count() == 0)
        {
            $c_data['slug'] = $_slug;
            $c_data['imported_on'] = $this->imported_on;
            $c_data['elevation_id'] = $home->id;
            $c_data['home_design_type_id'] = $design_type->id;
            $c_data['status_id'] = 1;
            if(strtolower($row[$this->mapChoice['design_type']]) == "Texture") {
                $c_data['design_type'] = 2;
            } else {
                $c_data['design_type'] = 1;
            }

            $c_data['is_default'] = "0";
            if(isset($row[$this->mapChoice['is_default']]) && $row[$this->mapChoice['is_default']]=="1") {
                HomeDesigns::where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->update(['is_default'=>'0']);
                $c_data['is_default'] = "1";
            }
            // download image from drive
            if(@$row[$this->mapChoice['thumbnail']]){
                $img = time().'opt.png';
                $path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id."/".$img);
                $base_url = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['thumbnail']],$path);
                if ($base_url) {
                    $c_data['thumbnail'] = $img;
                }
            }
            return new HomeDesigns($c_data);
        }
        elseif(HomeDesigns::where('title', 'like', $row[$this->mapChoice['title']])->where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->where("status_id","!=","2")->count()!=0 && $this->flag =='skip'){
            $data = serialize($c_data);
            ErrorHistory::create([
                'data'          => $data,
                'type'          => 'design_options',
                'flag'          => 'skip',
                'imported_on'   => $this->imported_on
            ]);
            return null;
        }
        elseif(HomeDesigns::where('title', 'like', $row[$this->mapChoice['title']])->where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->count()!=0 && ($this->flag =='update' || $this->flag =='skip')){
            $design = HomeDesigns::where('title', 'like', $row[$this->mapChoice['title']])->where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->first();
            $c_data['slug'] = $_slug;
            $c_data['imported_on'] = $this->imported_on;
            $c_data['elevation_id'] = $home->id;
            $c_data['home_design_type_id'] = $design_type->id;
            $c_data['status_id'] = 1;
            if(strtolower($row[$this->mapChoice['design_type']]) == "color") {
                $c_data['design_type'] = 1;
            } else {
                $c_data['design_type'] = 2;
            }
            
            $c_data['is_default'] = "0";
            if(isset($row[$this->mapChoice['is_default']]) && $row[$this->mapChoice['is_default']]=="1") {
                HomeDesigns::where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->update(['is_default'=>'0']);
                $c_data['is_default'] = "1";
            }
            
            // download image from drive
            $c_data['thumbnail'] = $design->thumbnail;
            if(isset($this->mapChoice['thumbnail']) && @$row[$this->mapChoice['thumbnail']]){
                $img = time().'opt.png';
                $path = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id."/".$img);
                $base_url = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['thumbnail']],$path);
                if ($base_url) {
                    $exist_file = public_path('media/uploads/exterior/'.$design_type->slug.'_'.$design_type->id."/".$design->thumbnail);
                    if(file_exists($exist_file)){
                        unlink($exist_file);
                    }
                    $c_data['thumbnail'] = $img;
                }
            }
            HomeDesigns::where('title', 'like', $row[$this->mapChoice['title']])->where(['home_design_type_id'=>$design_type->id,'elevation_id'=>$home->id])->update($c_data);
            return;
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
            'type'          =>'design_options',
            'imported_on'   => $this->imported_on
        ]);
    }
}
