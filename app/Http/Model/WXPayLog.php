<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class WXPayLog extends Model
{
    //
    protected $table='wx_paylog';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
