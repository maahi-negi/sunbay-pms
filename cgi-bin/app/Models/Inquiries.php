<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiries extends Model
{
    protected $table = 'inquiries';
    protected $guarded = [];

    public function Home() {
        return $this->belongsTo('App\Models\Homes', 'homeId');
    }

    public function elevation() {
        return $this->belongsTo('App\Models\Elevations', 'elevationId');
    }

}
