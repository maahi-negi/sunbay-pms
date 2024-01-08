<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    //
    protected $fillable = ['type','imported_on','imported_by','file_name','success','fail','skip'];
}
