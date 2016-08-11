<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ShipperCode extends Model
{
    //
    protected $table='shippercode';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
