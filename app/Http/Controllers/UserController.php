<?php

namespace App\Http\Controllers;

use App\http\Model\Addr;
use App\http\Model\Agent;
use App\http\Model\Cash;
use App\http\Model\Citys;
use App\http\Model\Config;
use App\http\Model\Follower;
use App\http\Model\Income;
use App\Http\Model\Users;
use Illuminate\Support\Facades\Crypt;

class UserController extends CommController
{
    public function register($phone, $psw)
    {
        if (!$this->checkPhone($phone)){
            return $this->rtnMsg(1, '亲，请输入正确的号码！');
        }
        if (strlen($psw) < 6){
            return $this->rtnMsg(1, '密码长度最少6位！');
        }
        $count = Users::where('phone', $phone)->count();
        if (0 != $count){
            return $this->rtnMsg(1, '该号码已经注册!');
        }

        $data = new Users;
        $data->phone = $phone;
        $data->psw = Crypt::encrypt($psw);
        if($data->save()) {
            //加入到根级粉丝
            $input = [
                'groupid'=>$data->id,
                'leftweight'=>1,
                'rightweight'=>2,
                'userid'=>$data->id,
                'layer'=>0,
            ];
            Follower::create($input);

            return $this->rtnMsg(0, '注册成功!');
        }
        else{
            return $this->rtnMsg(1, '注册失败，请稍候再试!');
        }
    }

    public function logIn($phone, $psw)
    {
        if (!$this->checkPhone($phone)){
            return $this->rtnMsg(1, '亲，请输入正确的号码！');
        }

        $user = Users::where('phone', $phone)->first();
        if (!$user || 0 == count($user)){
            return $this->rtnMsg(1, '号码不存在!');
        }

        if ($user->errorcount >= 5) {
            if ((time() - $user->errortime) < 5 * 60) {
                return $this->rtnMsg(1, '操作太频繁，请稍候再试！');
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

            return $this->rtnMsg(1, '密码错误！');
        }

        session(['user'=>$user]);
        $userBase = [
            'id'=>$user->id,
            'consume'=>$user->consume,
            'nickname'=>$user->nickname,
            'icon'=>$user->icon,
        ];

        return $this->rtnMsg(0, $userBase);
    }

    public function logOut()
    {
        session(['user'=>null]);

        return 0;
    }

    public function getUserBase()
    {
        $user = session('user');
        $user = Users::select('id', 'consume', 'nickname', 'icon')->find($user['id']);
        return $this->rtnMsg(0, $user);
    }

    public function getUserInfo()
    {
        $user = session('user');
        $user = Users::find($user->id);
        $user->psw = null;

        return $this->rtnMsg(0, $user);
    }

    public function bindAccount($phone, $psw)
    {
        if (!$this->checkPhone($phone)){
            return $this->rtnMsg(1, '亲，请输入正确的号码！');
        }
        if (strlen($psw) < 6){
            return $this->rtnMsg(1, '密码长度最少6位！');
        }

        $user = session('user');
        $have = Users::where('phone', $phone)->count();
        if (0 != $have){
            return $this->rtnMsg(1, '号码已经绑定!');
        }

        $input = [
            'phone'=>$phone,
            'psw'=>Crypt::encrypt($psw),
        ];
        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            $user = Users::find($user['id']);
            session(['user'=>$user]);

            return $this->rtnMsg(0, '绑定成功!');
        }else{
            return $this->rtnMsg(1, '绑定失败，请稍候再试!');
        }
    }

    public function changePsw($oldpsw, $newpsw)
    {
        if (strlen($newpsw) < 6){
            return $this->rtnMsg(1, '密码长度最少6位！');
        }
        $user = session('user');
        if (!$this->checkPhone($user['phone'])){
            return $this->rtnMsg(1, '请先绑定号码!');
        }

        $user = Users::find($user['id']);
        if (Crypt::decrypt($user['psw']) != $oldpsw){
            return $this->rtnMsg(1, '原密码验证失败!');
        }

        $input = [
            'psw'=>Crypt::encrypt($newpsw),
        ];
        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            return $this->rtnMsg(0, '密码修改成功!');
        }else{
            return $this->rtnMsg(1, '密码修改失败，请稍候再试!');
        }
    }

    public function changeUserInfo($info)
    {
        $input = json_decode($info);
        if (0 != strlen($input->email)){
            if (!$this->checkMail($input->email)){
                return $this->rtnMsg(1, '请输入有效的有效地址!');
            }
        }
        if ($this->checkStr($input->name)
            || $this->checkStr($input->nickname)
            || $this->checkStr($input->qq)
            || $this->checkStr($input->weixnumber)){
            return $this->rtnMsg(1, '请勿输入特殊字符!');
        }

        $user = session('user');
        $nickCount = Users::where('nickname', $input->nickname)->where('id', '<>', $user['id'])->count();
        if (0 != $nickCount){
            return $this->rtnMsg(1, '昵称重复啦!');
        }

        $input = [
            'name'=>$input->name,
            'nickname'=>$input->nickname,
            'email'=>$input->email,
            'qq'=>$input->qq,
            'weixnumber'=>$input->weixnumber,
        ];

        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            return $this->rtnMsg(0, '资料修改成功!');
        }else{
            return $this->rtnMsg(1, '资料修改失败，请稍候再试!');
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
            return $this->rtnMsg(1, '请输入收货人姓名!');
        }
        if (0 == strlen($input->phone) || !$this->checkPhone($input->phone)){
            return $this->rtnMsg(1, '请输入有效的收货人联系电话!');
        }
        if (0 == strlen($input->addr)){
            return $this->rtnMsg(1, '请输入收货人联系地址!');
        }

        if ($this->checkStr($input->name)){
            return $this->rtnMsg(1, '请勿输入特殊字符!');
        }

        $user = session('user');
        $input = [
            'userid'=>$user['id'],
            'name'=>$input->name,
            'phone'=>$input->phone,
            'addr'=>$input->addr,
        ];

        $re = Addr::create($input);
        if ($re){
            return $this->rtnMsg(0, '保存收货地址成功!');
        }else{
            return $this->rtnMsg(1, '保存收货地址失败，请稍候再试!');
        }
    }

    public function getAddr()
    {
        $user = session('user');
        $addr = Addr::where('userid', $user['id'])->get();

        return $this->rtnMsg(0, $addr);
    }

    public function delAddr($id)
    {
        if (!is_numeric($id)){
            return $this->rtnMsg(1, '参数错误!');
        }
        $user = session('user');
        $re = Addr::where('id', $id)->delete();
        if($re){
            $addr = Addr::where('userid', $user['id'])->get();
            return $this->rtnMsg(0, $addr);
        }else{
            return $this->rtnMsg(1, '删除失败，请稍候再试!');
        }
    }

    public function agentShow()
    {
        $user = session('user');
        $have = Agent::where('userid', $user['id'])->count();
        if (0 != $have){
            return $this->rtnMsg(0, false);
        }else{
            return $this->rtnMsg(0, true);
        }
    }
    
    public function agent($name, $phone)
    {
        if (0 == strlen($name)){
            return $this->rtnMsg(1, '请输入真实姓名!');
        }
        if (0 == strlen($phone) || !$this->checkPhone($phone)){
            return $this->rtnMsg(1, '请输入联系电话!');
        }
        if ($this->checkStr($name)){
            return $this->rtnMsg(1, '请勿输入特殊字符!');
        }

        $user = session('user');
        $have = Agent::where('userid', $user['id'])->count();
        if (0 != $have){
            return $this->rtnMsg(1, '你已经申请过了，不需要重新申请!');
        }

        $input = [
            'userid'=>$user['id'],
            'name'=>$name,
            'phone'=>$phone,
            'state'=>0,
        ];

        $re = Agent::create($input);
        if ($re){
            return $this->rtnMsg(0, '申请成功!');
        }else{
            return $this->rtnMsg(1, '申请失败，请稍候再试!');
        }
    }

    public function canShowQRC()
    {
        $user = session('user');

        //是否显示
        $rtn = [];
        $config = Config::all()[0];
        $user = Users::select('consume', 'income')->find($user['id']);
        $rtn['Income'] = $user['income'];
        $rtn['Cash'] = $config['cash'];
        if ($user['consume'] >= $config['openspread']){
            $rtn['canShowQRC'] = true;
            return $this->rtnMsg(0, $rtn);
        }else{
            $rtn['canShowQRC'] = false;
            return $this->rtnMsg(0, $rtn);
        }
    }

    private function numPerPage()
    {
        return 20;
    }

    public function loadIncomeData($page)
    {
        $user = session('user');
        $income = Income::where('userid', $user['id'])->
            skip($page * $this->numPerPage())->take($this->numPerPage())->
            orderBy('time','desc')->get();

        return $this->rtnMsg(0, $income);
    }

    public function loadCashData($page)
    {
        if (!is_numeric($page)){
            return $this->rtnMsg(1, '参数错误!');
        }

        $user = session('user');
        $cash = Cash::where('userid', $user['id'])->
        skip($page * $this->numPerPage())->take($this->numPerPage())->
        orderBy('time','desc')->get();

        return $this->rtnMsg(0, $cash);
    }

    public function cash($money)
    {
        if (!is_numeric($money)){
            return $this->rtnMsg(1, '参数错误!');
        }
        $user = session('user');
        $config = Config::all()[0];
        if ($config['cash'] > $money){
            return $this->rtnMsg(1, '提现金额最少'.$config['cash'].'元!');
        }
        $user = Users::find($user['id']);
        if ($money * 100 > $user['income']){
            return $this->rtnMsg(1, '余额不足!');
        }

        $data = new Cash;
        $data->userid = $user['id'];
        $data->money = $money;
        $data->status = 0;
        $data->balance = $user['income'] - $money * 100;
        $data->time = time();
        if($data->save()) {
            $user['income'] =$data->balance;
            $re = $user->update();
            if (!$re){
                $data->delete();
                return $this->rtnMsg(1, '提现申请失败，请稍候再试!');
            }

            return $this->rtnMsg(0, '提现申请成功!');
        }
        else{
            return $this->rtnMsg(1, '提现申请失败，请稍候再试!');
        }
    }

    public function showLevel($followerid)
    {
        if (!is_numeric($followerid)){
            return $this->rtnMsg(1, '参数错误!');
        }

        $user = session('user');
        $myLayer = Follower::where('userid', $user['id'])->first();
        $followerLayer = Follower::where('userid', $followerid)->first();

        return $this->rtnMsg(0, $followerLayer['layer'] - $myLayer['layer']);
    }
}
