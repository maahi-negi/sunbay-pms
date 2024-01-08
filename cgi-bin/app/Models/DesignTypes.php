<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignTypes extends Model
{
    //
    protected $table = 'design_types';
    protected $guarded = [];

    public function designs(){
        return $this->hasMany('App\Models\Designs', 'design_type_id')->where('status_id',1)->orderBy('title');
    }
    
     public function categories(){
        return $this->hasMany('App\Models\DesignCategory', 'design_type')->where('type',2);
    }
}
