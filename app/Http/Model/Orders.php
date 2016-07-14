<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected $table='orders';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
