<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\AdminUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends CommonController
{
    public function index()
    {
        $uerNam = session('user')->user_name;
        return view('admin.index', compact('uerNam'));
    }

    public function info()
    {
        return view('admin.info');
    }

    //更改密码
    public function pass()
    {
        if($input = Input::all()){
            $rules = [
                'password'=>'required|between:6,20|confirmed',
            ];
            $message = [
                'password.required'=>'新密码不能为空！',
                'password.between'=>'新密码必须在6-20位之间！',
                'password.confirmed'=>'新密码和确认密码不一致！',
            ];

            $validator = Validator::make($input,$rules,$message);

            if($validator->passes()){
                $user = AdminUser::where('user_name', session('user')->user_name)->first();
                if (!$user
                    || 0 == count($user)){
                    session(['user'=>null]);
                    return redirect('admin/login')->with('msg','未找到用户！');
                }

                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o']==$_password){
                    if ($_password == $input['password']){
                        return back()->with('errors','新密码不能与原密码相同！');
                    }

                    $user->user_pass = Crypt::encrypt($input['password']);
                    $user->update();
                    return back()->with('errors','密码修改成功！');
                }else{
                    return back()->with('errors','原密码错误！');
                }
            }else{
                return back()->withErrors($validator);
            }

        }else{
            return view('admin.pass');
        }
    }
}
