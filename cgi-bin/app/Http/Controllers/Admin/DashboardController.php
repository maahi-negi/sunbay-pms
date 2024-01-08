<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HomeDesigns;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public $data;
    public function index(){
        $this->data['menu'] = 'home';
        $this->data['design_groups_count'] = 1;
        return view('admin.dashboard')->with($this->data);
    }
    
}
