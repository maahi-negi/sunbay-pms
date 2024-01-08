<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeDesigns extends Model
{
    protected $table = 'home_designs';
    protected $guarded = [];

    public function design_type()
    {
        return $this->belongsTo('App\Models\HomeDesignTypes');
    }

}
