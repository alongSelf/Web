<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    //
    protected $table='adminuser';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
