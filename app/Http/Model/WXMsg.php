<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class WXMsg extends Model
{
    //
    protected $table='wx_msg';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
