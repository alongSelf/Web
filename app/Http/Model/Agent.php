<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    //
    protected $table='agent';
    protected $primaryKey='userid';
    public $timestamps=false;
    protected $guarded=[];
}
