<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\ErrorHistory;
use App\Models\Homes;

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
class HomePlanImport implements ToModel, WithHeadingRow,WithValidation,SkipsOnFailure
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
        if(array_key_exists('title',$this->mapChoice))
        {
            $this->rules[$this->mapChoice['title']] = 'required';
        }
        if(array_key_exists('base_image',$this->mapChoice))
        {
            $this->rules[$this->mapChoice['base_image']] = 'required';
        }
    }

    public function model(array $row)
    {
        //continue or stop
        if(!array_key_exists('title',$this->mapChoice)) {
            // there is no field map with community name. Terminate the whole process and exit.
            return;
        }
        foreach($this->mapChoice as $key => $value) {
            $c_data[$key] =  $row[$this->mapChoice[$key]];
        }

        $slug = str_replace(' ', '-', strtolower($row[$this->mapChoice['title']]));
        if(Homes::where('slug', $slug)->count() == 0)
        {
            $c_data['status_id'] = '1';
            $c_data['slug'] = $slug;
            $c_data['imported_on'] = $this->imported_on;
            // download image from drive
            if(isset($this->mapChoice['base_image'],$row[$this->mapChoice['base_image']]) && $row[$this->mapChoice['base_image']]){
                $img = time().'.png';
                $path = public_path('media/uploads/exterior/'.$img);
                $base_url = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['base_image']],$path);
                if ($base_url) {
                    $c_data['base_image'] = $img;
                }
            }
            // Handle exception
            $homePlan = Homes::create($c_data);
        }
        elseif(Homes::where('slug', $slug)->where("status_id","!=","2")->count() != 0 && $this->flag =='skip'){
            $data = serialize($c_data);
            ErrorHistory::create([
                'data'          => $data,
                'type'          => 'homePlan',
                'flag'          => 'skip',
                'imported_on'   => $this->imported_on
            ]);
            return null;
        }
        elseif(Homes::where('slug', $slug)->count() != 0 && ($this->flag =='update' || $this->flag =='skip')){
            $home = Homes::where('slug', $slug)->first();
            $c_data['imported_on'] = $this->imported_on;
            $c_data['status_id'] = 1;
            $c_data['base_image'] = $home->base_image;
            // download image from drive
            if(@$row[$this->mapChoice['base_image']]){
                $img = time().'.png';
                $path = public_path('media/uploads/exterior/'.$img);
                $base_url = \App\Http\Controllers\API\ImportController::accessFileDrive($row[$this->mapChoice['base_image']],$path);
                if ($base_url) {
                    $exist_file = public_path('media/uploads/exterior/'.$home->base_image);
                    if(file_exists($exist_file)){
                        unlink($exist_file);
                    }
                    $c_data['base_image'] = $img;
                }
            }
            Homes::where('slug',$slug)->update($c_data);
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
            'type'          => 'homePlan',
            'imported_on'   => $this->imported_on
        ]);
    }
}
