<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Evaluates extends Model
{
    //
    protected $table='evaluates';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
