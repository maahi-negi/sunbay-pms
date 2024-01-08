<?php

namespace App\Http\Controllers\API;

use App\Imports\ExcelHeadings;
use App\Imports\ManageImport;
use App\Models\ErrorHistory;
use App\Models\History;
use App\Models\HomeDesigns;
use App\Models\HomeDesignTypes;
use App\Models\Homes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{

    /**
     * Read the sheet and send the columns available in the sheets
     */
    public function getColumnsToMap(Request $request)
    {
        # code...
        if($request->excelFile)
        {
            if($request->session()->has('excel'))
            {
                $dir = public_path('/uploads/excel/');
                $path = $dir.$request->session()->get('excel');
                if(file_exists('/uploads/excel/')){
                    unlink($path);
                }
            }

            $array = (new ExcelHeadings)->toArray($request->excelFile);
            $data = [];
            $data['headings'] = $array;
            if(array_key_exists('Home Plans',$array)) {
                $data['home_plans'] = ['title'=>'HomePlan Title','base_image'=>'Base Image'];
            }
            if(array_key_exists('Design Types',$array)) {
                $data['design_type'] = ['elevation_id'=>'HomePlan Title','title'=>'Design Type Title','thumbnail'=>'Thumbnail','image_view1'=>'Base Layer','image_view2'=>'Base Layer 2','image_view3'=>'Base Layer 3','priority'=>'Priority'];
            }
            if(array_key_exists('Design Options',$array)) {
                $data['design_options'] = ['elevation_id'=>'HomePlan Title','home_design_type_id'=>'Design Type Title','title'=>'Option Title','is_default' => "Default Option",'design_type'=>'Color/Texture','product_id'=>'SW Code','rgb_color'=>'RGB Color','hex_color'=>'Hex Color','thumbnail'=>'Thumbnail'];
            }

            $file = $request->excelFile;
            $name = $file->getClientOriginalName();
            $destination = public_path('/uploads/excel/');
            $file->move($destination,$name);
            $request->session()->put('excel',$name);
            return $data;
        }
        else
        {
            $dir = public_path('/uploads/excel/');

            if($request->session()->has('excel'))
            {
                $path = $dir.$request->session()->get('excel');
                unlink($path);
            }
            $googleSheetUploaded = $this->downloadGoogleSheet($request);
            if($googleSheetUploaded)
            {
                $file_name = $dir.$request->session()->get('excel');
                $array = (new ExcelHeadings)->toArray($file_name);


                $data = [];
                $data['headings'] = $array;
                if(array_key_exists('Home Plans',$array)) {
                    $data['home_plans'] = ['title'=>'HomePlan Title','base_image'=>'Base Image'];
                }
                if(array_key_exists('Design Types',$array)) {
                    $data['design_type'] = ['elevation_id'=>'HomePlan Title','title'=>'Design Type Title','thumbnail'=>'Thumbnail','image_view1'=>'Base Layer','image_view2'=>'Base Layer 2','image_view3'=>'Base Layer 3','priority'=>'Priority'];
                }
                if(array_key_exists('Design Options',$array)) {
                    $data['design_options'] = ['elevation_id'=>'HomePlan Title','home_design_type_id'=>'Design Type Title','title'=>'Option Title','is_default' => "Default Option",'design_type'=>'Color/Texture','product_id'=>'SW Code','rgb_color'=>'RGB Color','hex_color'=>'Hex Color','thumbnail'=>'Thumbnail'];
                }
                return $data;
            }
            else
            {
                abort(400, 'Unauthorized action.');
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $importing_on = time();
        $mapArray = json_decode($request->mapped);
        $dir = public_path('/uploads/excel/');
        $path = $dir.$request->session()->get('excel');
        $flag = $mapArray->import_as;
        Excel::import(new ManageImport($mapArray,$importing_on,$flag), $path);
        // $array = ();
        // dd($array);

        $hm_success = Homes::where('imported_on',$importing_on)->count();
        $dt_success = HomeDesignTypes::where('imported_on',$importing_on)->count();
        $do_success = HomeDesigns::where('imported_on',$importing_on)->count();

        $hm_fail = ErrorHistory::where(['imported_on'=>$importing_on,'type' =>'homePlan','flag'=>'error'])->count();
        $dt_fail = ErrorHistory::where(['imported_on'=>$importing_on,'type' =>'design_type','flag'=>'error'])->count();
        $do_fail = ErrorHistory::where(['imported_on'=>$importing_on,'type' =>'design_options','flag'=>'error'])->count();
        $total_skip = ErrorHistory::where(['imported_on'=>$importing_on,'flag'=>'skip'])->count();
        $total_fail = $hm_fail+$dt_fail+$do_fail;
        $total_success = $hm_success+$dt_success+$do_success;

        $total = $total_fail + $total_success;
        if($total !=0)
        {
            $success_percent = (($total_success)/($total_fail+$total_success))*100;
            $success_percent = round($success_percent);
            $res = array(
                'success'       =>$total_success-$total_skip,
                'skip'          => $total_skip,
                'fail'          => $total_fail,
                'percentage'    => $success_percent
            );
            History::where('imported_on',$importing_on)->update([
                'success'    =>$total_success-$total_skip,
                'fail'       =>$total_fail,
                'skip'       =>$total_skip,
                'percent'    => $success_percent
            ]);
        }
        else
        {
            $res = array('success' =>0, 'fail' => 0, 'percentage' => 0);
            History::where('imported_on',$importing_on)->update([
                'success'=>0, 'fail'=>0,'percent' => 0
            ]);
        }
        $request->session()->forget('excel');
        if(file_exists($path)){
            unlink($path);
        }
        return response()->json($res);
    }

    static function accessFileDrive($file_link,$path){
        // download image from drive
        if($file_link){
            $image_url = explode("/view",$file_link);
            $image_url = explode("/",$image_url[0]);
            $image_id = $image_url[count($image_url)-1];
            $url = "https://drive.google.com/uc?id=$image_id";
        
            file_put_contents($path, file_get_contents($url));
            return true;
        }
        return false;
    }

    public function downloadGoogleSheet(Request $request)
    {
        $dir = public_path('/uploads/excel/');

        //unique file name
        $base_name = time();
        $file_name = $dir.$base_name.'_google.xlsx';
        $sheetId = $this->returnId($request->url);
        if($sheetId)
        {
            // Initialize a file URL to the variable
            $url = 'https://docs.google.com/spreadsheets/d/'.$sheetId.'/export?format=xlsx';

            if(file_put_contents( $file_name,file_get_contents($url))) {
                $request->session()->put('excel',$base_name.'_google.xlsx');
                return true;
            }
            else {
                $request->session()->forget('excel');
                return false;
            }
        }
        else{
            $request->session()->forget('excel');
            return false;
        }
    }

    public function returnId($url):string
    {
        $array = explode('/',$url);
        $index = array_search('d',$array);
        if($index)
        {
            return $array[$index+1];
        }
        else{
            return false;
        }
    }
}
