<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class UserController extends CommonController
{
    //
    public function index()
    {
        $data = Users::orderBy('id','desc')->paginate(10);

        return view('admin.user.index', compact('data'));
    }

    public function resetPSW()
    {
        $id = Input::get('id');
        $newPSW = Crypt::encrypt('123456');

        $user = Users::find($id);
        $user->psw = $newPSW;
        $re = $user->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '重置成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '重置失败，请稍后重试！',
            ];
        }
        return $data;
    }

    public function search($val, $type)
    {
        switch ($type)
        {
            case 'phone':
                $data = Users::where('phone', $val)->orderBy('id','desc')->paginate(10);
                break;
            case 'name':
                $data = Users::where('name','like','%'.$val.'%')->orderBy('id','desc')->paginate(10);
                break;
            case 'nickname':
                $data = Users::where('nickname','like','%'.$val.'%')->orderBy('id','desc')->paginate(10);
                break;
            case 'wx':
                $data = Users::where('weixnumber','like','%'.$val.'%')->orderBy('id','desc')->paginate(10);
                break;
            case 'qq':
                $data = Users::where('qq','like','%'.$val.'%')->orderBy('id','desc')->paginate(10);
                break;
            default:
                break;
        }

        return view('admin.user.index', compact('data'));
    }
}
