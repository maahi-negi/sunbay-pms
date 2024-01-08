<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
    public function homePage(){
        $this->data['title'] = 'ULE - Home page';
        return view('home-page')->with($this->data);
    }

}
