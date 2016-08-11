<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    //
    protected $table='income';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
