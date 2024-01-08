<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public $data;
    public function index(){
        $this->data['menu'] = '';
        $this->data['admin'] = Users::where([
            'id' => Auth::user()->id,
            'user_role_id' => 1
            ])->first();
        return view('admin.profile')->with($this->data);
    }
}
