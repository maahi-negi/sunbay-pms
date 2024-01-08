<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeDesignTypes extends Model
{
    //
    protected $table = 'home_design_types';
    protected $guarded = [];

    public function designs(){
        return $this->hasMany('App\Models\HomeDesigns', 'home_design_type_id')->where('status_id',1)->orderBy('title');
    }

    public function categories(){
        return $this->hasMany('App\Models\DesignCategory', 'design_type')->where('type',1);
    }
}
