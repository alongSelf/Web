<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\AdminUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

//require_once 'resources/code/Code.class.php';

class LoginController extends CommonController
{
    public function login()
    {
        if($input = Input::all()){
            //$code = new \Code;
            //$_code = $code->get();
            //if(strtoupper($input['code']) != $_code){
            //    return back()->with('msg','验证码错误！');
            //}

            $user = AdminUser::where('user_name', $input['user_name'])->first();
            if(!$user || 0 == count($user)){
                return back()->with('msg','用户名不存在！');
            }

            if ($user->errorcount >= 5) {
                if ((time() - $user->errortime) < 5 * 60) {
                    return back()->with('msg', '操作太频繁，请稍候再试！');
                }
                else {
                    $user->errorcount = 0;
                    $user->errortime = 0;

                    $user->update();
                }
            }

            if ($user->user_pass && 0 != strlen($user->user_pass)){
                if (Crypt::decrypt($user->user_pass) != $input['user_pass']){
                    $user->errorcount++;
                    $user->errortime = time();

                    $user->update();

                    return back()->with('msg','密码错误！');
                }
            }

            session([BSessionNam=>$user]);
            return redirect('admin/index');

        }else {
            return view('admin.login');
        }
    }

    public function quit()
    {
        session([BSessionNam=>null]);
        return redirect('admin/login');
    }

    //public function verificationCode()
    //{
    //    $code = new \Code;
    //    $code->make();
    //}
}
