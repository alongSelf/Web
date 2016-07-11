<?php

namespace App\Http\Controllers;

use App\Http\Model\Users;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    private function checkPhone($phone)
    {
        if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/", $phone)){
            return true;
        }else{
            return false;
        }
    }

    private function rtnLogIn($code, $msg)
    {
        return $data = [
            'status' => $code,
            'msg' => $msg,
        ];
    }

    public function logIn($phone, $psw)
    {
        if (!$this->checkPhone($phone)){
            return $this->rtnLogIn(1, '亲，请输入正确的号码！');
        }

        $user = Users::where('phone', $phone)->first();
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '号码不存在!');
        }

        if ($user->errorcount >= 5) {
            if ((time() - $user->errortime) < 5 * 60) {
                return $this->rtnLogIn(1, '操作太频繁，请稍候再试！');
            }
            else {
                $user->errorcount = 0;
                $user->errortime = 0;

                $user->update();
            }
        }

        if (Crypt::decrypt($user->psw) != $psw){
            $user->errorcount++;
            $user->errortime = time();

            $user->update();

            return $this->rtnLogIn(1, '密码错误！');
        }

        session(['user'=>$user]);
        $userBase = [
            'id'=>$user->id,
            'consume'=>$user->consume,
            'nickname'=>$user->nickname,
            'icon'=>$user->icon,
        ];

        return $this->rtnLogIn(0, $userBase);
    }

    public function logOut()
    {
        session(['user'=>null]);

        return 0;
    }

    public function getUserBase()
    {
        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $userBase = [
            'id'=>$user->id,
            'consume'=>$user->consume,
            'nickname'=>$user->nickname,
            'icon'=>$user->icon,
        ];

        return $this->rtnLogIn(0, $userBase);
    }

    public function getUserInfo()
    {
        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $user = Users::find($user->id);
        $user->psw = null;

        return $this->rtnLogIn(0, $user);
    }

    public function bindAccount()
    {

    }

    public function changePsw()
    {

    }

    public function setInfo()
    {

    }
}
