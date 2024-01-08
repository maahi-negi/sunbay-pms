<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Homes;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class BulkUploadController extends Controller
{
    //
    public function __construct()
    {
        $this->title = 'Uploads';
        $this->data['page_title'] = $this->title;
        $this->data['menu'] = 'uploads';
    }

    public function returnBulkDataView()
    {
        return view ('admin.bulk-data')->with($this->data);
    }

   public function getDropDownOptions($type)
   {
        switch($type)
        {
            case 'community':
            return $this->getAllCommunities();
            break;
            case 'elevation':
            return $this->getAllHomes();
            break;
            case 'floor':
            return $this->getAllFloors();
            break;
            case 'floor-feature':
            return $this->getAllFloorsFeatures();
            break;
            case 'color-scheme':
            return $this->getAllColorSchemes();
            break;
            case 'color-scheme-feature':
            return $this->getAllColorSchemeFeatures();
            break;
            default:
            break;
        }
   }
    public function getAllCommunities()
    {
        # code...
        $communites = Communities::get(['id','name']);
        return $communites;
    }
    public function getAllHomes()
    {
        # code...
        $homes = Homes::get(['id','title']);
        return $homes;
    }
    public function getAllFloors()
    {
        # code...
        $floors = Floor::get();
        foreach($floors as $floor)
        {
            $home = Homes::where('id',$floor->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';

            $floor->title = $home_title.'-'.$floor->title;
        }
        return $floors;
    }
    public function getAllFloorsFeatures()
    {
        $features = Features::where('parent_id','!=',0)->get();
        foreach($features as $feature)
        {
            $floor = Floor::where('id',$feature->floor_id)->first();
            $floor_title = isset($floor)?$floor->title:'No floor';
            if($floor)
            $home = Homes::where('id',$floor->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $feature->title = $home_title.'-'.$floor_title.'-'.$feature->title;
        }
        return $features;
    }
    public function getAllColorSchemes()
    {
        $color_scheme = ColorSchemes::get();
        foreach($color_scheme as $color)
        {
            $home = Homes::where('id',$color->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';

            $color->title = $home_title.'-'.$color->title;
        }
        return $color_scheme;
    }
    public function getAllColorSchemeFeatures()
    {
        $features = HomeFeatures::get();
        foreach($features as $feature)
        {
            $color_scheme = ColorSchemes::where('id',$feature->color_scheme_id)->first();
            $color_scheme_title = isset($color_scheme)?$color_scheme->title:'No Color Scheme';
            if($color_scheme)
            $home = Homes::where('id',$color_scheme->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $feature->title = $home_title.'-'.$color_scheme_title.'-'.$feature->title;
        }
        return $features;
    }
    public function getAllHomesForCommunity($id)
    {
        # code...
        $homeIds = CommunitiesHomes::where('communities_id',$id)->pluck('homes_id');
        $homes = Homes::whereIn('id',$homeIds)->where('parent_id',0)->get(['id','title']);
        return $homes;
    }
    public function getAllHomeRelatedData($id)
    {
        # code...
        $data =[];
        $homes = Homes::where('parent_id',$id)->get(['id','title']);
        $colorscheme = ColorSchemes::where('home_id',$id)->get(['id','title']);
        $floor = Floor::where('home_id',$id)->get(['id','title']);
        $data['home_type'] = $homes;
        $data['color_scheme'] =  $colorscheme;
        $data['floor'] = $floor;
        return $data;
    }
    public function getAllHomeTypesColorSchemeForHomeType($id)
    {
        # code...
        $colorscheme = ColorSchemes::where('home_id',$id)->get(['id','title']);
        return $colorscheme;
    }
    public function getAllHomesFloor($id)
    {
        # code...
        $floor = Floors::where('home_id',$id)->get(['id','title']);
        return $floor;
    }
    public function unmappedImages(Request $request)
    {
        # code...
        $data = [];
        switch($request->type){
            case '':
                $data['floor'] = $this->getAllFloorUnmappedImages(true);
                $data['home'] = $this->getAllHomeUnMappedImages(true);
                $data['community'] = $this->getCommunityUnmappedImages(true);
                $data['feature'] = $this->getAllFloorFeaturesUnmappedImages(true);
                return $data;
            break;
            case 'communities':
                $data['floor'] = [];
                $data['home'] = [];
                $data['community'] = $this->getCommunityUnmappedImages(true);
                $data['feature'] = [];
                return $data;
            break;
            case 'elevations':
                $data['floor'] = [];
                $data['home'] = $this->getAllHomeUnMappedImages(true);
                $data['community'] = [];
                $data['feature'] = [];
                return $data;
            break;
            case 'floors':
                $data['floor'] = $this->getAllFloorUnmappedImages(true);
                $data['home'] = [];
                $data['community'] = [];
                $data['feature'] = [];
                return $data;
            break;
            case 'features':
                $data['floor'] = [];
                $data['home'] = [];
                $data['community'] = [];
                $data['feature'] = $this->getAllFloorFeaturesUnmappedImages(true);
                return $data;
            break;
            default:
        break;
        }
    }
    public function storeImgTemporary(Request $request)
    {
        $sessionId = $request->session()->getId();
        $destinationPath = public_path('uploads/temp/').$sessionId;
        if($request->session()->has('temp_image'))
        {
            $this->cleanTempImg($destinationPath,$request->session());
        }
        $data = [];
        $data['mapped']['community'] = [];
        $data['mapped']['elevation'] = [];
        $data['mapped']['floor'] = [];
        $data['mapped']['floor_feature'] = [];
        $data['mapped']['color_scheme']= [];
        $data['mapped']['color_scheme_feature']= [];
        $data['unmapped'] = [];
        $data['uploaded_by'] = Admins::whereId(Auth::user()->id)->first()->name;
        $filterOptions = json_decode($request->type);
        if($request->file)
        {
            $number_of_images = count($request->file);
            for($i = 0;$i<$number_of_images;$i++)
            {
                $image = $request->file[$i];
                $name  = $image->getClientOriginalName();
                $image->move($destinationPath, $name);
                $result = $this->processAndMatch(strtolower(explode('.',$name)[0]),$filterOptions);
                if(count($result))
                {
                    $temp = [
                        'section' => $result[1],
                        'name' => $name,
                        'value' => $result[2],
                        'path' => $sessionId.'/'.$name,
                        'id' => $result[3]
                    ];
                    if($result[0] == 'community')
                        array_push($data['mapped']['community'],$temp);
                    if($result[0] == 'elevation')
                        array_push($data['mapped']['elevation'],$temp);

                    if($result[0] == 'floor')
                        array_push($data['mapped']['floor'],$temp);

                    if($result[0] == 'floor-feature')
                        array_push($data['mapped']['floor_feature'],$temp);

                    if($result[0] == 'color-scheme')
                        array_push($data['mapped']['color_scheme'],$temp);

                    if($result[0] == 'color-scheme-feature')
                        array_push($data['mapped']['color_scheme_feature'],$temp);

                }
                 else
                {
                    $temp = [
                        'name' => $name,
                        'path' => $sessionId.'/'.$name,
                    ];
                    array_push($data['unmapped'],$temp);
                }
            }
            $request->session()->put('temp_image',true);
        }
        return response()->json($data);
    }

    public function updateImage(Request $request)
    {
        $sessionId = $request->session()->getId();
        $destinationPath = public_path('uploads/temp/').$sessionId;
        if($request->file){
            unlink($destinationPath.'/'.$request->previous_file);
            $image = $request->file;
            $name = $image->getClientOriginalName();
            $image->move($destinationPath,$name);
        }
    }

    public function confirmedUploadNow(Request $request)
    {
        $timestamp = time();
        $inProcess_com = true;
        $inProcess_ele = true;
        History::create([
            'type' => 'image',
            'imported_on' => $timestamp,
            'imported_by' => Auth::user()->id,
            'file_name' => 'image_upload'.$timestamp.'.xlsx'
        ]);
        $imported_as = $request->import_as;
        $sessionId = $request->session()->getId();
        $tempDestinationPath = public_path('uploads/temp/').$sessionId;
        $value = json_decode($request->mapped);
        // $type,$type_id,$section,$name,$time,$status
        if($value->community){
            $destinationPath = public_path('uploads/');
            foreach($value->community as $com){
                if(copy($tempDestinationPath.'/'.$com->image_name,$destinationPath.$com->image_name)){
                    unlink($tempDestinationPath.'/'.$com->image_name);
                }
                else{
                    unlink($tempDestinationPath.'/'.$com->image_name);
                    $this->storeImageHistory('community',$com->id,$com->section,$com->image_name,$timestamp,'fail');
                    continue;
                }
                if($imported_as == 'skip'){
                    switch($com->section){

                        case 'logo':
                            $logo = Communities::whereId($com->id)->get(['logo'])->first();
                            if($logo->logo){
                                $this->storeImageHistory('community',$com->id,'logo',$com->image_name,$timestamp,'skip');
                            }
                            else{
                                Communities::whereId($com->id)->update(['logo'=>$com->image_name,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('community',$com->id,'logo',$com->image_name,$timestamp,'success');
                            }
                        break;

                        case 'banner':
                            $banner = Communities::whereId($com->id)->get(['banner'])->first();
                            if($banner->banner){
                                $this->storeImageHistory('community',$com->id,'banner',$com->image_name,$timestamp,'skip');
                            }
                            else{
                                Communities::whereId($com->id)->update(['banner'=>$com->image_name,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('community',$com->id,'banner',$com->image_name,$timestamp,'success');
                            }
                        break;

                        case 'map':
                            $map = Communities::whereId($com->id)->get(['marker_image'])->first();
                            if($map->marker_image){
                                $this->storeImageHistory('community',$com->id,'marker_image',$com->image_name,$timestamp,'skip');
                            }
                            else{
                                Communities::whereId($com->id)->update(['marker_image'=>$com->image_name,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('community',$com->id,'marker_image',$com->image_name,$timestamp,'success');
                            }
                        break;

                        case 'gallery':
                            $gallery = Communities::whereId($com->id)->get(['gallery'])->first();

                            if($gallery->gallery){
                                $gallery = explode(',',$gallery->gallery);
                                if(in_array($com->image_name,$gallery)){
                                    $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'skip');
                                }
                                else{
                                    array_push($gallery,$com->image_name);
                                    $gallery = implode(',',$gallery);
                                    Communities::whereId($com->id)->update(['gallery'=>$gallery,'imported_on'=>$timestamp]);
                                    $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'success');
                                }
                            }
                            else{
                                $g = implode(',',[$com->image_name]);
                                Communities::whereId($com->id)->update(['gallery' =>$g]);
                                $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'success');
                            }
                        break;

                        default:
                        break;
                    }
                }
                elseif($imported_as == 'update'){
                    switch($com->section){

                        case 'logo':
                            Communities::whereId($com->id)->update(['logo'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('community',$com->id,'logo',$com->image_name,$timestamp,'success');
                        break;

                        case 'banner':
                            Communities::whereId($com->id)->update(['banner'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('community',$com->id,'banner',$com->image_name,$timestamp,'success');
                        break;

                        case 'map':
                            Communities::whereId($com->id)->update(['marker_image'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('community',$com->id,'marker_image',$com->image_name,$timestamp,'success');
                        break;

                        case 'gallery':
                            $gallery = Communities::whereId($com->id)->get(['gallery'])->first();
                            if($gallery->gallery){
                                $gallery = explode(',',$gallery->gallery);
                                array_push($gallery,$com->image_name);
                                $gallery = implode(',',$gallery);
                                Communities::whereId($com->id)->update(['gallery'=>$gallery,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'success');
                            }
                            else{
                                $g = implode(',',[$com->image_name]);
                                Communities::whereId($com->id)->update(['gallery' =>$g]);
                                $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'success');
                            }
                        break;

                        default:
                        break;
                    }
                }
                else{
                    switch($com->section){
                        case 'logo':
                            Communities::whereId($com->id)->update(['logo'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('community',$com->id,'logo',$com->image_name,$timestamp,'success');
                        break;

                        case 'banner':
                            Communities::whereId($com->id)->update(['banner'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('community',$com->id,'banner',$com->image_name,$timestamp,'success');
                        break;

                        case 'map':
                            Communities::whereId($com->id)->update(['marker_image'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('community',$com->id,'marker_image',$com->image_name,$timestamp,'success');
                        break;

                        case 'gallery':
                            $gallery = Communities::whereId($com->id)->get(['gallery'])->first();
                            if($gallery->gallery){
                                if($inProcess_com){
                                    $gallery = implode(',',[$com->image_name]);
                                    $inProcess_com = false;
                                }
                                else{
                                    $gallery = explode(',',$gallery->gallery);
                                    array_push($gallery,$com->image_name);
                                    $gallery = implode(',',$gallery);
                                }
                                Communities::whereId($com->id)->update(['gallery'=>$gallery,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'success');
                            }
                            else{
                                $g = implode(',',[$com->image_name]);
                                Communities::whereId($com->id)->update(['gallery' =>$g]);
                                $this->storeImageHistory('community',$com->id,'gallery',$com->image_name,$timestamp,'success');
                            }
                        break;

                        default:
                        break;
                    }
                }

            }
        }
        if($value->elevation){
            $destinationPath = public_path('uploads/homes/');
            foreach($value->elevation as $ele){
                if(copy($tempDestinationPath.'/'.$ele->image_name,$destinationPath.$ele->image_name)){
                    unlink($tempDestinationPath.'/'.$ele->image_name);
                }
                else{
                    unlink($tempDestinationPath.'/'.$ele->image_name);
                    $this->storeImageHistory('elevation',$ele->id,$ele->section,$ele->image_name,$timestamp,'fail');
                    continue;
                }
                if($imported_as == 'skip'){
                    switch($ele->section){

                        case 'feature-image':
                            $feature = Homes::whereId($ele->id)->get(['img'])->first();
                            if($feature->img){
                                $this->storeImageHistory('elevation',$ele->id,'feature-image',$ele->image_name,$timestamp,'skip');
                            }
                            else{
                                Homes::whereId($ele->id)->update(['img'=>$ele->image_name,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('elevation',$ele->id,'feature-image',$ele->image_name,$timestamp,'success');
                            }
                        break;

                        case 'gallery':
                            $gallery = Homes::whereId($ele->id)->get(['gallery'])->first();
                            if($gallery->gallery){
                                $gallery = explode(',',$gallery->gallery);
                                if(in_array($ele->image_name,$gallery)){
                                    $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'skip');
                                }
                                else{
                                    array_push($gallery,$ele->image_name);
                                    $gallery = implode(',',$gallery);
                                    Homes::whereId($ele->id)->update(['gallery'=>$gallery,'imported_on'=>$timestamp]);
                                    $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'success');
                                }
                            }
                            else{
                                $g = implode(',',[$ele->image_name]);
                                Homes::whereId($ele->id)->update(['gallery' =>$g]);
                                $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'success');
                            }
                        break;

                        default:
                        break;
                    }
                }
                elseif($imported_as =='update'){
                    switch($ele->section){
                        case 'feature-image':
                            Homes::whereId($com->id)->update(['img'=>$com->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('elevation',$ele->id,'img',$ele->image_name,$timestamp,'success');
                        break;

                        case 'gallery':
                            $gallery = Homes::whereId($ele->id)->get(['gallery'])->first();
                            if($gallery->gallery){
                                $gallery = explode(',',$gallery->gallery);
                                array_push($gallery,$ele->image_name);
                                $gallery = implode(',',$gallery);
                                Homes::whereId($ele->id)->update(['gallery'=>$gallery,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'success');
                            }
                            else{
                                $g = implode(',',[$ele->image_name]);
                                Homes::whereId($ele->id)->update(['gallery' =>$g]);
                                $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'success');
                            }
                        break;

                        default:
                        break;
                    }
                }
                else{
                    switch($ele->section){
                        case 'feature-image':
                            Homes::whereId($ele->id)->update(['img'=>$ele->image_name,'imported_on'=>$timestamp]);
                            $this->storeImageHistory('elevation',$ele->id,'img',$ele->image_name,$timestamp,'success');
                        break;

                        case 'gallery':
                            $gallery = Homes::whereId($ele->id)->get(['gallery'])->first();
                            if($gallery->gallery){
                                if($inProcess_ele){
                                    $gallery = implode(',',[$ele->image_name]);
                                    $inProcess_ele = false;
                                }
                                else{
                                    $gallery = explode(',',$gallery->gallery);
                                    array_push($gallery,$ele->image_name);
                                    $gallery = implode(',',$gallery);
                                }
                                Homes::whereId($ele->id)->update(['gallery'=>$gallery,'imported_on'=>$timestamp]);
                                $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'success');
                            }
                            else{
                                $g = implode(',',[$ele->image_name]);
                                Homes::whereId($ele->id)->update(['gallery' =>$g]);
                                $this->storeImageHistory('elevation',$ele->id,'gallery',$ele->image_name,$timestamp,'success');
                            }
                        break;

                        default:
                        break;
                    }
                }
            }
        }
        if($value->color_scheme){
            $destinationPath = public_path('uploads/homes/');
            foreach($value->color_scheme as $color){
                if(copy($tempDestinationPath.'/'.$color->image_name,$destinationPath.$color->image_name)){
                    unlink($tempDestinationPath.'/'.$color->image_name);
                }
                else{
                    unlink($tempDestinationPath.'/'.$color->image_name);
                    $this->storeImageHistory('color-scheme',$color->id,$color->section,$color->image_name,$timestamp,'fail');
                    continue;
                }
                if($imported_as == 'skip'){
                    $color_scheme = ColorSchemes::whereId($color->id)->get(['img'])->first();
                    if($color_scheme->img){
                        $this->storeImageHistory('color-scheme',$color->id,'feature-image',$color->image_name,$timestamp,'skip');
                    }
                    else{
                        ColorSchemes::whereId($color->id)->update(['img'=>$color->image_name,'imported_on'=>$timestamp]);
                        $this->storeImageHistory('color-scheme',$color->id,'feature-image',$color->image_name,$timestamp,'success');
                    }
                }
                else{
                    ColorSchemes::whereId($color->id)->update(['img'=>$color->image_name,'imported_on'=>$timestamp]);
                    $this->storeImageHistory('color-scheme',$color->id,'feature-image',$color->image_name,$timestamp,'success');
                }
            }
        }
        if($value->color_scheme_feature){
            $destinationPath = public_path('uploads/homes/');
            foreach($value->color_scheme_feature as $color_feature){
                if(copy($tempDestinationPath.'/'.$color_feature->image_name,$destinationPath.$color_feature->image_name)){
                    unlink($tempDestinationPath.'/'.$color_feature->image_name);
                }
                else{
                    unlink($tempDestinationPath.'/'.$color_feature->image_name);
                    $this->storeImageHistory('color-scheme-feature',$color_feature->id,$color_feature->section,$color_feature->image_name,$timestamp,'fail');
                    continue;
                }
                if($imported_as == 'skip'){
                    $feature = HomeFeatures::whereId($color_feature->id)->get(['img'])->first();
                    if($feature->img){
                        $this->storeImageHistory('color-scheme-feature',$color_feature->id,'feature-image',$color_feature->image_name,$timestamp,'skip');
                    }
                    else{
                        HomeFeatures::whereId($color_feature->id)->update(['img'=>$color_feature->image_name,'imported_on'=>$timestamp]);
                        $this->storeImageHistory('color-scheme-feature',$color_feature->id,'feature-image',$color_feature->image_name,$timestamp,'success');
                    }
                }
                else{
                    HomeFeatures::whereId($color_feature->id)->update(['img'=>$color_feature->image_name,'imported_on'=>$timestamp]);
                    $this->storeImageHistory('color-scheme-feature',$color_feature->id,'feature-image',$color_feature->image_name,$timestamp,'success');
                }
            }
        }
        if($value->floor){
            $destinationPath = public_path('uploads/floors/' );
            foreach($value->floor as $floor){
                if(copy($tempDestinationPath.'/'.$floor->image_name,$destinationPath.$floor->image_name)){
                    unlink($tempDestinationPath.'/'.$floor->image_name);
                }
                else{
                    unlink($tempDestinationPath.'/'.$floor->image_name);
                    $this->storeImageHistory('floor',$floor->id,$floor->section,$floor->image_name,$timestamp,'fail');
                    continue;
                }
                if($imported_as == 'skip'){
                    $temp_floor = Floor::whereId($floor->id)->get(['image'])->first();
                    if($temp_floor->image){
                        $this->storeImageHistory('floor',$floor->id,'feature-image',$floor->image_name,$timestamp,'skip');
                    }
                    else{
                        Floor::whereId($floor->id)->update(['image'=>$floor->image_name,'imported_on'=>$timestamp]);
                        $this->storeImageHistory('floor',$floor->id,'feature-image',$floor->image_name,$timestamp,'success');
                    }
                }
                else{
                    Floor::whereId($floor->id)->update(['img'=>$color_feature->image_name,'imported_on'=>$timestamp]);
                    $this->storeImageHistory('floor',$floor->id,'feature-image',$floor->image_name,$timestamp,'success');
                }
            }
        }
        if($value->floor_feature){
            $destinationPath = public_path('uploads/features/');
            foreach($value->floor_feature as $feature){
                if(copy($tempDestinationPath.'/'.$feature->image_name,$destinationPath.$feature->image_name)){
                    unlink($tempDestinationPath.'/'.$feature->image_name);
                }
                else{
                    unlink($tempDestinationPath.'/'.$feature->image_name);
                    $this->storeImageHistory('floor-feature',$feature->id,$feature->section,$feature->image_name,$timestamp,'fail');
                    continue;
                }
                if($imported_as == 'skip'){
                    $temp_feature = Features::whereId($feature->id)->get(['image'])->first();
                    if($temp_feature->image){
                        $this->storeImageHistory('floor-feature',$feature->id,'feature-image',$feature->image_name,$timestamp,'skip');
                    }
                    else{
                        Features::whereId($feature->id)->update(['image'=>$feature->image_name,'imported_on'=>$timestamp]);
                        $this->storeImageHistory('floor-feature',$feature->id,'feature-image',$feature->image_name,$timestamp,'success');
                    }
                }
                else{
                    Features::whereId($feature->id)->update(['image'=>$feature->image_name,'imported_on'=>$timestamp]);
                    $this->storeImageHistory('floor-feature',$feature->id,'feature-image',$feature->image_name,$timestamp,'success');
                }
            }
        }
        $total_skip = ImageHistory::where(['imported_on'=>$timestamp,'status'=>'skip'])->count();
        $total_success = ImageHistory::where(['imported_on'=>$timestamp,'status'=>'success'])->count();
        $total_fail = ImageHistory::where(['imported_on'=>$timestamp,'status'=>'fail'])->count();
        $total = $total_fail + $total_success+$total_skip;
        if($total !=0)
        {
            $success_percent = (($total_success+$total_skip)/($total_fail+$total_success+$total_skip))*100;
            $success_percent = round($success_percent);
            $res = array(
                'success'       =>$total_success,
                'skip'          => $total_skip,
                'fail'          => $total_fail,
                'percentage'    => $success_percent
            );
            History::where(['imported_on'=>$timestamp,'type'=>'image'])->update([
                'success'    =>$total_success,
                'fail'       =>$total_fail,
                'skip'       =>$total_skip,
                'percent'    => $success_percent
            ]);
        }
        else
        {
            $res = array(
                'success'       =>0,
                'fail'          => 0,
                'skip'          =>0,
                'percentage'    => 0
            );
            History::where('imported_on',$importing_on)->update([
                'success'    =>0,
                'fail'       =>0,
                'skip'       =>0,
                'percent' => 0
            ]);
        }
        $this->cleanTempImg($tempDestinationPath,$request->session());
        return response()->json($res);
    }

    public function storeImageHistory($type,$type_id,$section,$name,$time,$status)
    {
        ImageHistory::create([
            'type'=>$type,
            'type_id'=>$type_id,
            'section'=>$section,
            'name'=>$name,
            'imported_on'=>$time,
            'status'=>$status
        ]);
        return true;
    }

    public function uploadBulkImage(Request $request)
    {
        # code...
        $type = $request->type;
        if($request->file)
        {
            $number_of_images = count($request->file);
            for($i = 0;$i<$number_of_images;$i++)
            {
                $image = $request->file[$i];
                $name  = $image->getClientOriginalName();
                switch($type){
                    case 'communities':
                        $destinationPath = public_path('uploads');
                    break;
                    case 'elevations':
                        $destinationPath = public_path('uploads/homes');
                    break;
                    case 'floors':
                        $destinationPath = public_path('uploads/floors');
                    break;
                    case 'features':
                        $destinationPath = public_path('uploads/features');
                    break;
                    default:
                    break;
                }
                $image->move($destinationPath, $name);
            }
            return response()->json([
                'status' =>200,
                'msg' => 'All the images uploaded successfully'
            ]);
        }
    }
    private function processAndMatch($name,$filter=null):array
    {
        $nameArray = explode('-',$name);
        $matchOrUnmatch = [];
        // name shoulde be without extension
        if($filter){
            switch($filter->type){

                case 'community':
                    $find = Communities::whereId($filter->sub_type)->first();
                    array_push($matchOrUnmatch,'community');
                    array_push($matchOrUnmatch,$filter->section);
                    array_push($matchOrUnmatch,$find->name);
                    array_push($matchOrUnmatch,$find->id);
                    return $matchOrUnmatch;
                break;

                case 'elevation':
                    $find = Homes::whereId($filter->sub_type)->first();
                    array_push($matchOrUnmatch,'elevation');
                    array_push($matchOrUnmatch,$filter->section);
                    array_push($matchOrUnmatch,$find->title);
                    array_push($matchOrUnmatch,$find->id);
                    return $matchOrUnmatch;
                break;

                case 'floor':
                    $find = Floor::whereId($filter->sub_type)->first();
                    array_push($matchOrUnmatch,'floor');
                    array_push($matchOrUnmatch,$filter->section);

                    $home = Homes::where('id',$find->home_id)->first();
                    $home_title = isset($home)?$home->title:'No home';
                    $title = $home_title.'-'.$find->title;

                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$find->id);
                    return $matchOrUnmatch;
                break;

                case 'floor-feature':

                    array_push($matchOrUnmatch,'floor-feature');
                    array_push($matchOrUnmatch,$filter->section);

                    $feature = Features::whereId($filter->sub_type)->first();
                    $floor = Floor::where('id',$feature->floor_id)->first();
                    $floor_title = isset($floor)?$floor->title:'No floor';
                    if($floor)
                    $home = Homes::where('id',$floor->home_id)->first();
                    $home_title = isset($home)?$home->title:'No home';
                    $title = $home_title.'-'.$floor_title.'-'.$feature->title;

                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$feature->id);
                    return $matchOrUnmatch;
                break;

                case 'color-scheme':
                    array_push($matchOrUnmatch,'color-scheme');
                    array_push($matchOrUnmatch,$filter->section);

                    $find = ColorSchemes::whereId($filter->sub_type)->first();
                    $home = Homes::where('id',$find->home_id)->first();
                    $home_title = isset($home)?$home->title:'No home';
                    $title = $home_title.'-'.$find->title;

                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$find->id);
                    return $matchOrUnmatch;
                break;

                case 'color-scheme-feature':
                    array_push($matchOrUnmatch,'color-scheme-feature');
                    array_push($matchOrUnmatch,$filter->section);

                    $feature = HomeFeatures::whereId($filter->sub_type)->firts();
                    $color_scheme = ColorSchemes::where('id',$feature->color_scheme_id)->first();
                    $color_scheme_title = isset($color_scheme)?$color_scheme->title:'No Color Scheme';
                    if($color_scheme)
                    $home = Homes::where('id',$color_scheme->home_id)->first();
                    $home_title = isset($home)?$home->title:'No home';
                    $feature->title = $home_title.'-'.$color_scheme_title.'-'.$feature->title;

                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$find->id);
                    return $matchOrUnmatch;
                break;

                default:
                break;

            }
        }
        $find = Communities::where('name','like','%'.$nameArray[0])->first();
        if($find)
        {
            $section = 'banner';
            array_push($matchOrUnmatch,'community');

            $gallery = '-g-';
            $logo = '-logo';
            $marker = '-map-marker';
            if(strpos($name,$gallery)!== false){
                $section = "gallery";
                array_push($matchOrUnmatch,$section);
                array_push($matchOrUnmatch,$find->name);
                array_push($matchOrUnmatch,$find->id);
                return $matchOrUnmatch;
            }
            if(strpos($name,$logo)!== false)
            {
                $section = "logo";
                array_push($matchOrUnmatch,$section);
                array_push($matchOrUnmatch,$find->name);
                array_push($matchOrUnmatch,$find->id);
                return $matchOrUnmatch;
            }

            if(strpos($name,$marker)!== false)
            {
                $section = "marker";
                array_push($matchOrUnmatch,$section);
                array_push($matchOrUnmatch,$find->name);
                array_push($matchOrUnmatch,$find->id);
                return $matchOrUnmatch;
            }
            array_push($matchOrUnmatch,$section);
            array_push($matchOrUnmatch,$find->name);
            array_push($matchOrUnmatch,$find->id);
            return $matchOrUnmatch;
        }
        $find = Homes::where('title', 'LIKE', '%'.$nameArray[0])->first(); //If found may be home, type, floor, feature, color scheme, homefeature
        // $searchType = count($nameArray)>1?Homes::where('title', 'LIKE', '%'.$nameArray[1])->first():'';
        if($find)
        {
            $section = 'featured_image';
            $gallery = '-g';
            if(strpos($name,$gallery)!== false){
                $section = "gallery";
                array_push($matchOrUnmatch,'elevation');
                array_push($matchOrUnmatch,$section);
                array_push($matchOrUnmatch,$find->title);
                array_push($matchOrUnmatch,$find->id);
                return $matchOrUnmatch;
            }
            $explode_name = explode('-',$name);
            if(array_pop($explode_name) == 'f'){
                $index = count($explode_name)-2;
                $floor = Floor::where('home_id',$find->id)->where('title','like','%'.$explode_name[$index])->first();
                if($floor){
                    array_push($matchOrUnmatch,'floor');
                    array_push($matchOrUnmatch,$section);
                    $title = $find->title.'-'.$floor->title;
                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$floor->id);
                    return $matchOrUnmatch;
                }
            }

            if(array_pop($explode_name) == 'ff'){
                $index = count($explode_name)-2;
                $floor = Floor::where('home_id',$find->id)->first();
                if($floor){
                    $feature = Features::where('floor_id',$floor->id)->where('title','like','%'.$explode_name[$index])->first();
                    array_push($matchOrUnmatch,'floor-feature');
                    array_push($matchOrUnmatch,$section);
                    $title = $find->title.'-'.$floor->title.'-'.$feature->title;
                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$feature->id);
                    return $matchOrUnmatch;
                }
            }

            if(array_pop($explode_name) == 'cs'){
                $index = count($explode_name)-2;
                $color = ColorSchemes::where('home_id',$find->id)->where('title','like','%'.$explode_name[$index])->first();
                if($color){
                    array_push($matchOrUnmatch,'color-scheme');
                    array_push($matchOrUnmatch,$section);
                    $title = $find->title.'-'.$color->title;
                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$color->id);
                    return $matchOrUnmatch;
                }
            }
            if(array_pop($explode_name) =='csf'){
                $index = count($explode_name)-2;
                $color = ColorSchemes::where('home_id',$find->id)->first();
                if($color){
                    $feature = HomeFeature::where('color_scheme_id',$color->id)->where('title','like','%'.$explode_name[$index])->first();
                    array_push($matchOrUnmatch,'color-scheme-feature');
                    array_push($matchOrUnmatch,$section);
                    $title = $find->title.'-'.$color->title.'-'.$feature->title;
                    array_push($matchOrUnmatch,$title);
                    array_push($matchOrUnmatch,$feature->id);
                    return $matchOrUnmatch;
                }
            }
            // pattern for floor or colorscheme as well as features
            array_push($matchOrUnmatch,'elevation');
            array_push($matchOrUnmatch,$section);
            array_push($matchOrUnmatch,$find->title);
            array_push($matchOrUnmatch,$find->id);
            return $matchOrUnmatch;
        }
        return [];
        // to return the type matched with, entity name, and subtype matched.
    }

    //Clean the temp images
    public function cleanTempImg($dir,$session)
    {
        $files = scandir($dir);
        $files = array_diff($files,array('.','..'));
        foreach ($files as &$value) {
            unlink($dir.'/'.$value);
        }
        rmdir($dir);
        $session->forget('temp_image');
    }
    public function getImagesForSelectedType(Request $request)
    {
        # code...
        $dirName = $request->type;
        $images = [];
        $path = public_path('uploads\\'.$dirName);
        $files = scandir($path);
        foreach ($files as &$value) {
            $ext = (explode('.',$value));
            if(array_key_exists(1,$ext))
            {
            if(strtolower($ext[1])=='png' || strtolower($ext[1])=='jpg'||strtolower($ext[1])=='jpeg')
            array_push($images,$value);
            }
        }
        return $images;
    }
    public function imagesExistInDatabaseForSelectedType(Request $request)
    {
        # code...
        $data = [];
        $id = $request->id;
        switch($request->type){
            case 'community':
                $data['community'] = $this->getCommunityRelatedImages($id);
                $data['home'] = $this->getHomeRelatedImages($id);
                $data['feature'] = $this->floorFeaturesImages($id);
                $color_scheme = $this->getColorSchemeImages($id);
                $color_scheme_upgrade = $this->getColorSchemeUpgradeImages($id);
                $home_feature = $this->getHomeFeaturesImages($id);
                $data['floor'] = $this->getFloorImages($id);
                $data['home'] = array_merge($data['home'],$color_scheme,$color_scheme_upgrade,$home_feature);
                return $data;
            break;

            case 'home':
                $data['community'] = [];
                $data['home'] = $this->getHomeRelatedImages(null,$id);
                $color_scheme = $this->getColorSchemeImages(null,$id);
                $data['feature'] = $this->floorFeaturesImages(null,$id);
                $data['floor'] = $this->getFloorImages(null,$id);
                $color_scheme_upgrade = $this->getColorSchemeUpgradeImages(null,$id);
                $home_feature = $this->getHomeFeaturesImages(null,$id);
                $data['home'] = array_merge($data['home'],$color_scheme,$color_scheme_upgrade,$home_feature);
                return $data;
            break;

            case 'home-type':
                $data['community'] = [];
                $data['home'] = $this->getHomeRelatedImages(null,$id);
                $data['floor'] = $this->getFloorImages(null,$id);
                $data['feature'] = $this->floorFeaturesImages(null,$id);
                $color_scheme = $this->getColorSchemeImages(null,$id);
                $color_scheme_upgrade = $this->getColorSchemeUpgradeImages(null,$id);
                $home_feature = $this->getHomeFeaturesImages(null,$id);
                $data['home'] = array_merge($data['home'],$color_scheme,$color_scheme_upgrade,$home_feature);
                return $data;
            break;

            case 'color-scheme':
                $data['community'] = [];
                $data['home'] = [];
                $data['feature'] = [];
                $color_scheme = $this->getColorSchemeImages(null,null,$id);
                $color_scheme_upgrade = $this->getColorSchemeUpgradeImages(null,$id);
                $home_feature = $this->getHomeFeaturesImages(null,$id);
                $data['home'] = array_merge($data['home'],$color_scheme,$color_scheme_upgrade,$home_feature);
                return $data;
            break;

            case 'floor':
                $data['community'] = [];
                $data['home'] = [];
                $data['feature'] = $this->floorFeaturesImages(null,null,$id);
                $data['floor'] = $this->getFloorImages(null,null,$id);
                return $data;
            break;
            default:
            break;

        }
    }
    public function getCommunityRelatedImages($id)
    {
        $data = [];
        $getAll = Communities::whereId($id)->get()->first();
        $banner = $getAll->banner;
        $logo = $getAll->logo;
        $marker = $getAll->marker_image;
        $gallery = explode(',',$getAll->gallery);
        $svgImage = Plots::where('community_id',$id)->pluck('image')->toArray();
        array_push($data,$banner);
        array_push($data,$banner);
        array_push($data,$marker);
        $data = array_merge($data,$gallery,$svgImage);
        return $data;
    }
    public function getHomeRelatedImages($community_id=null,$home_id=null)
    {
        # code...
        $data = [];
        $homeGallery = [];
        $homeImages = [];
        if($community_id){
            $homeIds = CommunitiesHomes::where('communities_id',$community_id)->pluck('homes_id');
            $homes = Homes::whereIn('id',$homeIds)->get(['gallery','img']);
            foreach($homes as $img){
                array_push($homeImages,$img->img);
                $img = explode(',',$img->gallery);
                $homeGallery = array_merge($homeGallery,$img);
            }
        }
        if($home_id){
            $homeIds = Homes::where('parent_id',$home_id)->pluck('id')->toArray();
            array_push($homeIds,$home_id);
            $homes = Homes::whereIn('id',$homeIds)->get(['gallery','img']);
            foreach($homes as $img){
                array_push($homeImages,$img->img);
                $img = explode(',',$img->gallery);
                $homeGallery = array_merge($homeGallery,$img);
            }
        }
       $data = array_merge($homeImages,$homeGallery);
        return $data;
    }
    public function getColorSchemeImages($community_id=null,$home_id=null,$color_scheme_id=null)
    {
        # code...
        if($community_id)
        {
            $homeIds = CommunitiesHomes::where('communities_id',$community_id)->pluck('homes_id')->toArray();
            $images = ColorSchemes::whereIn('home_id',$homeIds)->pluck('img')->toArray();
        }
        if($color_scheme_id){

            $images = ColorSchemes::where('id',$color_scheme_id)->pluck('img')->toArray();
        }
        if($home_id)
        {
            $images = ColorSchemes::where('home_id',$home_id)->pluck('img')->toArray();
        }
        return $images;
    }
    public function getColorSchemeUpgradeImages($community_id=null,$color_scheme_id=null)
    {
        # code...
        if($community_id){
            $homeIds = CommunitiesHomes::where('communities_id',$community_id)->pluck('homes_id')->toArray();
            $colorIds = ColorSchemes::whereIn('home_id',$homeIds)->pluck('id')->toArray();
            $images = ColorSchemeUpgrade::whereIn('color_scheme_id',$colorIds)->pluck('img')->toArray();
        }
        else{
            $images = ColorSchemeUpgrade::where('color_scheme_id',$color_scheme_id)->pluck('img')->toArray();
        }
        return $images;
    }
    public function getHomeFeaturesImages($community_id=null,$color_scheme_id=null)
    {
        # code...
        if($community_id){
            $homeIds = CommunitiesHomes::where('communities_id',$community_id)->pluck('homes_id')->toArray();
            $colorIds = ColorSchemes::whereIn('home_id',$homeIds)->pluck('id')->toArray();
            $images = HomeFeatures::whereIn('color_scheme_id',$colorIds)->pluck('img')->toArray();
        }
        else{
        $images = HomeFeatures::where('color_scheme_id',$color_scheme_id)->pluck('img')->toArray();
        }
        return $images;
    }
    public function getFloorImages($community_id=null,$home_id=null,$floor_id=null)
    {
        # code...
        if($community_id)
        {
            $homeIds = CommunitiesHomes::where('communities_id',$community_id)->pluck('homes_id')->toArray();
            $images = Floor::whereIn('home_id',$homeIds)->pluck('image')->toArray();
        }
        if($home_id)
        {
            $images = Floor::where('home_id',$home_id)->pluck('image')->toArray();
        }
        if($floor_id)
        {
            $images = Floor::where('id',$floor_id)->pluck('image')->toArray();
        }
        return $images;

    }
    public function floorFeaturesImages($community_id=null,$home_id=null,$floor_id=null)
    {
        # code...
        if($community_id)
        {
            $homeIds = CommunitiesHomes::where('communities_id',$community_id)->pluck('homes_id')->toArray();
            $floorIds = Floor::whereIn('home_id',$homeIds)->pluck('id')->toArray();
            $images = Features::whereIn('floor_id',$floorIds)->pluck('image')->toArray();
        }
        if($home_id)
        {
            $floorIds = Floor::where('home_id',$home_id)->pluck('id')->toArray();
            $images = Features::whereIn('floor_id',$floorIds)->pluck('image')->toArray();
        }
        if($floor_id)
        {
            $images = Features::where('floor_id',$floor_id)->pluck('image')->toArray();
        }
        return $images;
    }
    public function deleteImage(Request $request)
    {
        # code...
        $dirName = $request->dir;
        if($dirName!=''){
            $dirPath = public_path('uploads/'.$dirName);
        }
        else{
            $dirPath = public_path('uploads');
        }
        $path = $dirPath.'/'.$request->image;
        unlink($path);
        return response()->json([
            'status'=>200,
            'msg'=>'deleted succesfully'
        ]);
    }
    public function getAllHomeUnMappedImages($inline=false)
    {
        # code...
        $gallery = [];
        $homeImages = [];
        $totalImagesInUse = [];
        $imagesInDirectory = [];
        $homeFeaturesImages = [];
        $unusedImages = [];
        $getAllGalleryImages = Homes::get();
        foreach($getAllGalleryImages as $img){
            array_push($homeImages,$img->img);
            $img = explode(',',$img->gallery);
            $gallery = array_merge($gallery,$img);
        }
        // color schemes images
        $colorSchemes = ColorSchemes::pluck('img')->toArray();

        // color schemes upgrade images
        $colorSchemesUpgrade = ColorSchemeUpgrade::pluck('img')->toArray();

        // home features images
        $homeFeaturesImages = HomeFeatures::pluck('img')->toArray();

        //merge all the images in the final array
        $totalImagesInUse = array_merge($homeImages,$gallery,$colorSchemes,$colorSchemesUpgrade,$homeFeaturesImages);

        // get all the images exist in home directory
        $path = public_path('uploads/homes');
        $files = scandir($path);
        foreach ($files as &$value) {
            $ext = (explode('.',$value));
            if(array_key_exists(1,$ext))
            {
            if(strtolower($ext[1])=='png' || strtolower($ext[1])=='jpg'||strtolower($ext[1])=='jpeg')
            array_push($imagesInDirectory,$value);
            }
        }
        $unusedImages = array_diff($imagesInDirectory,$totalImagesInUse);
        if($inline) return $unusedImages;
        else{
            $dirPath = public_path('uploads/homes\\');
            foreach($unusedImages as $img){
                $path = $dirPath.'\\'.$img;
                unlink($path);
            }
            return response()->json([
                'status'=>200,
                'database_images' => count($totalImagesInUse),
                'directory_images' =>count($imagesInDirectory),
                'difference'=>count($unusedImages),
                'unmapped_images'=> $unusedImages,
                'msg'=> 'Home Directory Cleaned'
            ]);
        }
    }
    public function getAllFloorUnmappedImages($inline=false)
    {
        # code...
        $imagesInDatabase = Floor::pluck('image')->toArray();
        $imagesInDirectory = [];
        $path = public_path('uploads/floors');
        $files = scandir($path);
        foreach ($files as &$value) {
            $ext = (explode('.',$value));
            if(array_key_exists(1,$ext))
            {
            if(strtolower($ext[1])=='png' || strtolower($ext[1])=='jpg'||strtolower($ext[1])=='jpeg')
            array_push($imagesInDirectory,$value);
            }
        }
        $unusedImages = array_diff($imagesInDirectory,$imagesInDatabase);
        if($inline) return $unusedImages;
        else{
            $dirPath = public_path('uploads/floors\\');
            foreach($unusedImages as $img){
                $path = $dirPath.'\\'.$img;
                unlink($path);
            }
            return response()->json([
                'status'=>200,
                'database_images' => count($imagesInDatabase),
                'directory_images' =>count($imagesInDirectory),
                'difference'=>count($unusedImages),
                'unmapped_images'=> $unusedImages,
                'msg'=> 'Floor Directory Cleaned'
            ]);
        }
    }
    public function getCommunityUnmappedImages($inline=false)
    {
        # code...
        $gallery = [];
        $totalImagesInUse = [];
        $imagesInDirectory = [];
        $unusedImages = [];
        $communityBanner = [];
        $communityLogo = [];
        $communityMarker = [];
        $getAllGalleryImages = Communities::get();
        foreach($getAllGalleryImages as $img){
            array_push($communityBanner,$img->banner);
            array_push($communityLogo,$img->logo);
            array_push($communityMarker,$img->marker_image);
            $img = explode(',',$img->gallery);
            $gallery = array_merge($gallery,$img);
        }
        $svgImage = Plots::pluck('image')->toArray();
        $totalImagesInUse = array_merge($communityBanner,$communityLogo,$communityMarker,$gallery,$svgImage);
        $path = public_path('uploads');
        $files = scandir($path);
        foreach ($files as &$value) {
            $ext = (explode('.',$value));
            if(array_key_exists(1,$ext))
            {
            if(strtolower($ext[1])=='png' || strtolower($ext[1])=='jpg'|| strtolower($ext[1])=='jpeg')
            array_push($imagesInDirectory,$value);
            }
        }
        $unusedImages = array_diff($imagesInDirectory,$totalImagesInUse);
        if($inline) return $unusedImages;
        else{
            $dirPath = public_path('uploads');
            foreach($unusedImages as $img){
                $path = $dirPath.'\\'.$img;
                unlink($path);
            }
            return response()->json([
                'status'=>200,
                'database_images' => count($totalImagesInUse),
                'directory_images' =>count($imagesInDirectory),
                'difference'=>count($unusedImages),
                'unmapped_images'=> $unusedImages,
                'msg'=> 'Community Directory Cleaned.'
            ]);
        }
    }
    public function getAllFloorFeaturesUnmappedImages($inline=false)
    {
        # code...
        $imagesInDatabase = Features::pluck('image')->toArray();
        $imagesInDirectory = [];
        $path = public_path('uploads/features');
        $files = scandir($path);
        foreach ($files as &$value) {
            $ext = (explode('.',$value));
            if(array_key_exists(1,$ext))
            {
            if(strtolower($ext[1])=='png' || strtolower($ext[1])=='jpg'||$ext[1]=='jpeg')
            array_push($imagesInDirectory,$value);
            }
        }
        $unusedImages = array_diff($imagesInDirectory,$imagesInDatabase);
        if($inline) return $unusedImages;
        else{
            $dirPath = public_path('uploads/features\\');
            foreach($unusedImages as $img){
                $path = $dirPath.'\\'.$img;
                unlink($path);
            }
            return response()->json([
                'status'=>200,
                'database_images' => count($imagesInDatabase),
                'directory_images' =>count($imagesInDirectory),
                'difference'=>count($unusedImages),
                'unmapped_images'=> $unusedImages,
                'msg'=> 'Floor Directory Cleaned'
            ]) ;
        }
    }
    public function cleanDir(Request $request)
    {
        # code...
        $type = $request->type;
        switch($type){
            case '':
                $home = $this->getAllHomeUnMappedImages(true);
                $community = $this->getCommunityUnmappedImages(true);
                $floor = $this->getAllFloorUnmappedImages(true);
                $feature = $this->getAllFloorFeaturesUnmappedImages(true);
                $total = count($home) + count($community) + count($floor) + count($feature);
                $homeDir = public_path('uploads/homes/');
                $comDir = public_path('uploads');
                $floorDir = public_path('uploads/floors/');
                $featureDir = public_path('uploads/features/');
                foreach($home as $img){
                    $path = $homeDir.'/'.$img;
                    unlink($path);
                }
                foreach($community as $img){
                    $path = $comDir.'/'.$img;
                    unlink($path);
                }
                foreach($floor as $img){
                    $path = $floorDir.'/'.$img;
                    unlink($path);
                }
                foreach($feature as $img){
                    $path = $featureDir.'/'.$img;
                    unlink($path);
                }
                return response()->json([
                    'status'=>200,
                    'unmapped_images'=> $total,
                    'msg'=> ' Directory Cleaned'
                ]);
            break;
            case 'communities':
                return $this->getCommunityUnmappedImages();
            break;
            case 'elevations':
                return $this->getAllHomeUnMappedImages();
            break;
            case 'floors':
                return $this->getAllFloorUnmappedImages();
            break;
            case 'features':
                return $this->getAllFloorFeaturesUnmappedImages();
            break;
            default:

            break;
        }
    }
  // Export the details to the excel
    public function exportImageError($timestamp)
    {
        # code...
        $data['community'] = [];
        $data['elevation'] = [];
        $data['color_scheme']   = [];
        $data['floor']  = [];
        $data['floor_feature']  = [];
        $data['color_scheme_feature'] = [];
        $data['floor_feature']  = [];

        // heading
        $data['heading'] = ['type','section','name','status'];

        //Community related data
        $com = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'community','status'=>'fail'])->get()->toArray();

        foreach($com as $s)
        {
            $s['type'] = Communities::whereId($s['type_id'])->get(['name'])->first()->name;
            $s['status'] = 'failed';
            unset($s['id'],$s['type_id'],$s['imported_on']);
            array_push($data['community'],$s);
        }

        //Elevation related data
        $ele = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'elevation','status'=>'fail'])->get()->toArray();

        foreach($ele as $temp_ele)
        {
            $temp_ele['type'] = Homes::whereId($temp_ele['type_id'])->get(['title'])->first()->title;
            $temp_ele['status'] = 'failed';
            unset($temp_ele['id'],$temp_ele['type_id'],$temp_ele['imported_on']);
            array_push($data['elevation'],$temp_ele);
        }
        // Floor related data
        $floor = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'floor','status'=>'fail'])->get()->toArray();

        foreach($floor as $temp_floor)
        {
            $find = Floor::whereId($temp_floor['type_id'])->get()->first();
            $home = Homes::where('id',$find->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $temp_floor['type'] = $home_title.'-'.$find->title;
            $temp_floor['status'] = 'failed';
            unset($temp_floor['id'],$temp_floor['type_id'],$temp_floor['imported_on']);
            array_push($data['floor'],$temp_floor);
        }
        // Floor Feature
        $floor_feature = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'floor-feature','status'=>'fail'])->get()->toArray();

        foreach($floor_feature as $temp_floor_feature)
        {
            $features = Features::whereId($temp_floor_feature['type_id'])->first();
            $floor = Floor::where('id',$feature->floor_id)->first();
            $floor_title = isset($floor)?$floor->title:'No floor';
            if($floor)
            $home = Homes::where('id',$floor->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $title = $home_title.'-'.$floor_title.'-'.$features->title;
            $temp_floor_feature['type'] = $title;
            $temp_floor_feature['status'] = 'failed';
            unset($temp_floor_feature['id'],$temp_floor_feature['type_id'],$temp_floor_feature['imported_on']);
            array_push($data['floor_feature'],$temp_floor);
        }

        // Color Schemes Related Data
        $color_scheme = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'color-scheme','status'=>'fail'])->get()->toArray();

        foreach($color_scheme as $temp_color_scheme)
        {
            $find = ColorSchemes::whereId($temp_color_scheme['type_id'])->first();
            $home = Homes::where('id',$find->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $title = $home_title.'-'.$find->title;
            $temp_color_scheme['type'] = $title;
            $temp_color_scheme['status'] = 'failed';
            unset($temp_color_scheme['id'],$temp_color_scheme['type_id'],$temp_color_scheme['imported_on']);
            array_push($data['color_scheme'],$temp_color_scheme);
        }

        // Color Scheme Feature
        $color_scheme_feature = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'color-scheme-feature','status'=>'fail'])->get()->toArray();

        foreach($color_scheme_feature as $temp_color_scheme_feature)
        {
            $feature = HomeFeatures::whereId($temp_color_scheme_feature['type_id'])->firts();
            $color_scheme = ColorSchemes::where('id',$feature->color_scheme_id)->first();
            $color_scheme_title = isset($color_scheme)?$color_scheme->title:'No Color Scheme';
            if($color_scheme)
            $home = Homes::where('id',$color_scheme->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $feature->title = $home_title.'-'.$color_scheme_title.'-'.$feature->title;
            $temp_color_scheme_feature['status'] = 'failed';
            array_push($data['color_scheme_feature'],$temp_color_scheme_feature);
        }
        // dd($data);
        $export_file_name = History::where(['imported_on'=>$timestamp,'type'=>'image'])->get(['file_name'])->first()->file_name;
        // dd($export_file_name);
        $export = new ManageImageDataExport($data,true);
        return Excel::download($export,$export_file_name);
    }

    public function exportImageSuccess($timestamp)
    {
        # code...
        $data['community'] = [];
        $data['elevation'] = [];
        $data['color_scheme']   = [];
        $data['floor']  = [];
        $data['floor_feature']  = [];
        $data['color_scheme_feature'] = [];
        $data['floor_feature']  = [];

        // heading
        $data['heading'] = ['type','section','name','status'];

        //Community related data
        $com = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'community','status'=>'success'])->get()->toArray();
        $skipped_community = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'community','status'=>'skip'])->get()->toArray();
        foreach($com as $s)
        {
            $s['type'] = Communities::whereId($s['type_id'])->get(['name'])->first()->name;
            $s['status'] = 'imported';
            unset($s['id'],$s['type_id'],$s['imported_on']);
            array_push($data['community'],$s);
        }
        foreach($skipped_community as $skip)
        {
            $skip['type'] = Communities::whereId($skip['type_id'])->get(['name'])->first()->name;
            $skip['status'] = 'skipped';
            unset($skip['id'],$skip['type_id'],$skip['imported_on']);
            array_push($data['community'],$skip);
        }
        //Elevation related data
        $ele = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'elevation','status'=>'success'])->get()->toArray();
        $skipped_ele = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'elevation','status'=>'skip'])->get()->toArray();

        foreach($ele as $temp_ele)
        {
            $temp_ele['type'] = Homes::whereId($temp_ele['type_id'])->get(['title'])->first()->title;
            $temp_ele['status'] = 'imported';
            unset($temp_ele['id'],$temp_ele['type_id'],$temp_ele['imported_on']);
            array_push($data['elevation'],$temp_ele);
        }
        foreach($skipped_ele as $temp_skip_ele)
        {
            $temp_skip_ele['type'] = Homes::whereId($temp_skip_ele['type_id'])->get(['title'])->first()->title;
            $temp_skip_ele['status'] = 'skipped';
            unset($temp_skip_ele['id'],$temp_skip_ele['type_id'],$temp_skip_ele['imported_on']);
            array_push($data['elevation'],$temp_skip_ele);
        }
        // Floor related data
        $floor = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'floor','status'=>'success'])->get()->toArray();
        $skipped_floor = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'floor','status'=>'skip'])->get()->toArray();

        foreach($floor as $temp_floor)
        {
            $find = Floor::whereId($temp_floor['type_id'])->get()->first();
            $home = Homes::where('id',$find->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $temp_floor['type'] = $home_title.'-'.$find->title;
            $temp_floor['status'] = 'imported';
            unset($temp_floor['id'],$temp_floor['type_id'],$temp_floor['imported_on']);
            array_push($data['floor'],$temp_floor);
        }
        foreach($skipped_floor as $temp_skip_floor)
        {
            $find = Floor::whereId($temp_floor['type_id'])->get()->first();
            $home = Homes::where('id',$find->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $temp_skip_floor['type'] = $home_title.'-'.$find->title;
            $temp_skip_floor['status'] = 'skipped';
            unset($temp_skip_floor['id'],$temp_skip_floor['type_id'],$temp_skip_floor['imported_on']);
            array_push($data['floor'],$temp_skip_floor);
        }
        // Floor Feature
        $floor_feature = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'floor-feature','status'=>'success'])->get()->toArray();
        $skipped_floor_feature = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'floor-feature','status'=>'skip'])->get()->toArray();

        foreach($floor_feature as $temp_floor_feature)
        {
            $features = Features::whereId($temp_floor_feature['type_id'])->first();
            $floor = Floor::where('id',$features->floor_id)->first();
            $floor_title = isset($floor)?$floor->title:'No floor';
            if($floor)
            $home = Homes::where('id',$floor->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $title = $home_title.'-'.$floor_title.'-'.$features->title;
            $temp_floor_feature['type'] = $title;
            $temp_floor_feature['status'] = 'imported';
            unset($temp_floor_feature['id'],$temp_floor_feature['type_id'],$temp_floor_feature['imported_on']);
            array_push($data['floor_feature'],$temp_floor);
        }
        foreach($skipped_floor_feature as $temp_skip_floor_feature)
        {
            $features = Features::whereId($temp_floor_feature['type_id'])->first();
            $floor = Floor::where('id',$features->floor_id)->first();
            $floor_title = isset($floor)?$floor->title:'No floor';
            if($floor)
            $home = Homes::where('id',$floor->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $title = $home_title.'-'.$floor_title.'-'.$features->title;
            $temp_floor_feature['type'] = $title;
            $temp_skip_floor_feature['status'] = 'skipped';
            unset($temp_skip_floor_feature['id'],$temp_skip_floor_feature['type_id'],$temp_skip_floor_feature['imported_on']);
            array_push($data['floor_feature'],$temp_skip_floor_feature);
        }
        // Color Schemes Related Data
        $color_scheme = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'color-scheme','status'=>'success'])->get()->toArray();
        $skipped_color_scheme = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'color-scheme','status'=>'skip'])->get()->toArray();

        foreach($color_scheme as $temp_color_scheme)
        {
            $find = ColorSchemes::whereId($temp_color_scheme['type_id'])->first();
            $home = Homes::where('id',$find->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $title = $home_title.'-'.$find->title;
            $temp_color_scheme['type'] = $title;
            $temp_color_scheme['status'] = 'imported';
            unset($temp_color_scheme['id'],$temp_color_scheme['type_id'],$temp_color_scheme['imported_on']);
            array_push($data['color_scheme'],$temp_color_scheme);
        }
        foreach($skipped_color_scheme as $temp_skip_color_scheme)
        {
            $find = ColorSchemes::whereId($temp_skip_color_scheme['type_id'])->first();
            $home = Homes::where('id',$find->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $title = $home_title.'-'.$find->title;
            $temp_skip_color_scheme['type'] = $title;
            $temp_skip_color_scheme['status'] = 'skipped';
            unset($temp_skip_color_scheme['id'],$temp_skip_color_scheme['type_id'],$temp_skip_color_scheme['imported_on']);
            array_push($data['color_scheme'],$temp_skip_color_scheme);
        }
        // Color Scheme Feature
        $color_scheme_feature = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'color-scheme-feature','status'=>'success'])->get()->toArray();
        $skipped_color_scheme_feature = ImageHistory::where(['imported_on'=>$timestamp,'type'=>'color-scheme-feature','status'=>'skip'])->get()->toArray();

        foreach($color_scheme_feature as $temp_color_scheme_feature)
        {
            $feature = HomeFeatures::whereId($temp_color_scheme_feature['type_id'])->firts();
            $color_scheme = ColorSchemes::where('id',$feature->color_scheme_id)->first();
            $color_scheme_title = isset($color_scheme)?$color_scheme->title:'No Color Scheme';
            if($color_scheme)
            $home = Homes::where('id',$color_scheme->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $feature->title = $home_title.'-'.$color_scheme_title.'-'.$feature->title;
            $temp_color_scheme_feature['status'] = 'imported';
            array_push($data['color_scheme_feature'],$temp_color_scheme_feature);
        }
        foreach($skipped_color_scheme_feature as $temp_skip_color_scheme_feature)
        {
            $feature = HomeFeatures::whereId($temp_color_scheme_feature['type_id'])->firts();
            $color_scheme = ColorSchemes::where('id',$feature->color_scheme_id)->first();
            $color_scheme_title = isset($color_scheme)?$color_scheme->title:'No Color Scheme';
            if($color_scheme)
            $home = Homes::where('id',$color_scheme->home_id)->first();
            $home_title = isset($home)?$home->title:'No home';
            $feature->title = $home_title.'-'.$color_scheme_title.'-'.$feature->title;
            $temp_color_scheme_feature['status'] = 'skipped';
            array_push($data['color_scheme_feature'],$temp_color_scheme_feature);
        }
        // dd($data);
        $export_file_name = History::where(['imported_on'=>$timestamp,'type'=>'image'])->get(['file_name'])->first()->file_name;
        // dd($export_file_name);
        $export = new ManageImageDataExport($data,true);
        return Excel::download($export,$export_file_name);
    }
}
