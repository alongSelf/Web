<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    //图片上传
    public function upload()
    {
        $file = Input::file('Filedata');
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
            $path = $file -> move(base_path().'/uploads',$newName);
            return $newName;
        }
    }

    public function removeFile($name)
    {
        if(0 == strlen($name)){
            return;
        }

        $filePath = base_path().'/uploads/'.$name;
        if (file_exists($filePath)){
            unlink($filePath);
        }
    }
}
