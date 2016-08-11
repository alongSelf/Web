<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    //
    protected $table='cash';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
