<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Citys extends Model
{
    //
    protected $table='t_prov_city_area';
    protected $primaryKey='areano';
    public $timestamps=false;
    protected $guarded=[];
}
