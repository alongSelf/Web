<?php

namespace App\Http\Controllers;

use App\http\Model\Addr;
use App\http\Model\Agent;
use App\http\Model\Citys;
use App\Http\Model\Users;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    private function checkPhone($phone)
    {
        if(preg_match('/^0?1[3|4|5|8][0-9]\d{8}$/', $phone)){
            return true;
        }else{
            return false;
        }
    }

    private function checkMail($mail)
    {
        if(preg_match('/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/', $mail)){
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

    public function bindAccount($phone, $psw)
    {
        if (!$this->checkPhone($phone)){
            return $this->rtnLogIn(1, '亲，请输入正确的号码！');
        }
        if (strlen($psw) < 6){
            return $this->rtnLogIn(1, '密码长度最少6位！');
        }

        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $have = Users::where('phone', $phone)->count();
        if (0 != $have){
            return $this->rtnLogIn(1, '号码已经绑定!');
        }

        $input = [
            'phone'=>$phone,
            'psw'=>Crypt::encrypt($psw),
        ];
        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            $user = Users::find($user['id']);
            session(['user'=>$user]);

            return $this->rtnLogIn(0, '绑定成功!');
        }else{
            return $this->rtnLogIn(1, '绑定失败，请稍候再试!');
        }
    }

    public function changePsw($oldpsw, $newpsw)
    {
        if (strlen($newpsw) < 6){
            return $this->rtnLogIn(1, '密码长度最少6位！');
        }
        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }
        if (0 == count($user['phone'])){
            return $this->rtnLogIn(1, '请先绑定号码!');
        }

        $user = Users::find($user['id']);
        if (Crypt::decrypt($user['psw']) != $oldpsw){
            return $this->rtnLogIn(1, '原密码验证失败!');
        }

        $input = [
            'psw'=>Crypt::encrypt($newpsw),
        ];
        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            return $this->rtnLogIn(0, '密码修改成功!');
        }else{
            return $this->rtnLogIn(1, '密码修改失败，请稍候再试!');
        }
    }

    public function changeUserInfo($info)
    {
        $input = json_decode($info);
        if (0 != strlen($input->email)){
            if (!$this->checkMail($input->email)){
                return $this->rtnLogIn(1, '请输入有效的有效地址!');
            }
        }
        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $input = [
            'name'=>$input->name,
            'email'=>$input->email,
            'qq'=>$input->qq,
            'weixnumber'=>$input->weixnumber,
        ];

        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            return $this->rtnLogIn(0, '资料修改成功!');
        }else{
            return $this->rtnLogIn(1, '资料修改失败，请稍候再试!');
        }
    }

    public function getArea1()
    {
        return Citys::where('arealevel', 1)->get();
    }
    public function getChildArea($parentNo)
    {
        return Citys::where('parentno', $parentNo)->get();
    }

    public function saveAddr($addr)
    {
        $input = json_decode($addr);
        if (0 == strlen($input->name)){
            return $this->rtnLogIn(1, '请输入收货人姓名!');
        }
        if (0 == strlen($input->phone) || !$this->checkPhone($input->phone)){
            return $this->rtnLogIn(1, '请输入有效的收货人联系电话!');
        }
        if (0 == strlen($input->addr)){
            return $this->rtnLogIn(1, '请输入收货人联系地址!');
        }

        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $input = [
            'userid'=>$user['id'],
            'name'=>$input->name,
            'phone'=>$input->phone,
            'addr'=>$input->addr,
        ];

        $re = Addr::create($input);
        if ($re){
            return $this->rtnLogIn(0, '保存收货地址成功!');
        }else{
            return $this->rtnLogIn(1, '保存收货地址失败，请稍候再试!');
        }
    }

    public function getAddr()
    {
        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $addr = Addr::where('userid', $user['id'])->get();

        return $this->rtnLogIn(0, $addr);
    }

    public function delAddr($id)
    {
        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $re = Addr::where('id',$id)->delete();
        if($re){
            $addr = Addr::where('userid', $user['id'])->get();
            return $this->rtnLogIn(0, $addr);
        }else{
            return $this->rtnLogIn(1, '删除失败，请稍候再试!');
        }
    }

    public function agent($name, $phone)
    {
        if (0 == strlen($name)){
            return $this->rtnLogIn(1, '请输入真实姓名!');
        }
        if (0 == strlen($phone) || !$this->checkPhone($phone)){
            return $this->rtnLogIn(1, '请输入联系电话!');
        }

        $user = session('user');
        if (!$user || 0 == count($user)){
            return $this->rtnLogIn(1, '请登录!');
        }

        $have = Agent::where('userid', $user['id'])->count();
        if (0 != $have){
            return $this->rtnLogIn(1, '你已经申请过了，不需要重新申请!');
        }

        $input = [
            'userid'=>$user['id'],
            'name'=>$name,
            'phone'=>$phone,
            'state'=>0,
        ];

        $re = Agent::create($input);
        if ($re){
            return $this->rtnLogIn(0, '申请成功!');
        }else{
            return $this->rtnLogIn(1, '申请失败，请稍候再试!');
        }
    }
}
