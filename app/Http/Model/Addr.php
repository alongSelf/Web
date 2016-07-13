<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class Addr extends Model
{
    //
    protected $table='addr';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
