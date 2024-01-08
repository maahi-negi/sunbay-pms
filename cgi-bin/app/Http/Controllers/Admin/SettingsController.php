<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;
use Validator;
use App\Validators\FloorValidator;
use Illuminate\Support\Facades\Hash;
use App\Models\Settings;
use App\Admins;
USE DB;
use File;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->title = 'Settings';
        $this->data['page_title'] = $this->title;
        $this->data['menu'] = 'settings';
    }

    public function index()
    {
        $setting = Settings::where('status', 1)->get();
        $this->data['setting'] = $setting;
        $this->data['menu'] = 'settings';
        return view('admin.settings.index')->with($this->data);
    }

    
    public function update(Request $request)
    {
        $data = $request->except(['_token']);
        foreach($data as $key => $value)
        {
            if ($request->file($key)) 
            {
                $value1 = $request->file($key);
                $value = time().'.'.$value1->getClientOriginalExtension();
                $destinationImagePath = public_path('/uploads/');
                $uploadStatus = $value1->move($destinationImagePath,$value);
            }
            $settings = Settings::where('name',$key)->update(['value'=>$value]);
        }
        return redirect()->back()->with('success', 'Settings has been updated successfully.');
    }

    public function updateAdmins(Request $request, $id)
    {
        $users = Admins::find($id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->mobile = $request->mobile;
        $users->username = $request->username;
        $users->status_id = $request->status;

        $users->save();

        return redirect()->back()->with('success', 'Admin Data Updated.');
    }
}