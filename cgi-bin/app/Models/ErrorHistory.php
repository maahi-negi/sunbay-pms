<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorHistory extends Model
{
    protected $fillable = ['data','imported_on','type','flag','msg'];
}
