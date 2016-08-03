<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class WXMenu extends Model
{
    //
    protected $table='wx_menu';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
