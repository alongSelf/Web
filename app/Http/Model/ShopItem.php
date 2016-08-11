<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    //
    protected $table='shopitem';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
