<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homes extends Model
{
    protected $table = 'homes';
    protected $guarded = [];

    public function elevations() {
        return $this->hasMany('App\Models\Elevations', 'home_id')->where('status_id', 1)->orderBy('title');
    }

    public function extelevations() {
        return $this->hasMany('App\Models\Elevations', 'home_id')->where('exterior', 1)->where('status_id', 1)->orderBy('title');
    }

    public function floorelevations() {
        return $this->hasMany('App\Models\Elevations', 'home_id')->where('floorplan', 1)->where('status_id', 1)->orderBy('title');
    }
}
