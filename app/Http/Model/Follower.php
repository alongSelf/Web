<?php

namespace App\http\Model;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    //
    protected $table='follower';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
}
