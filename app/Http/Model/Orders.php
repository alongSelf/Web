<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected $table='orders';
    protected $primaryKey='id';
    protected $keyType = 'string';
    public $timestamps=false;
    protected $guarded=[];
}
