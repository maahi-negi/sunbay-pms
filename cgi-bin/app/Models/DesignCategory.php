<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignCategory extends Model
{
    protected $table = 'design_categories';
    protected $guarded = [];

    public function sub_category(){
        return $this->hasMany('App\Models\DesignCategory', 'parent_id');
    }
}
