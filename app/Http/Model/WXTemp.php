<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class WXTemp extends Model
{
    //
    protected $table='wx_tmp';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
