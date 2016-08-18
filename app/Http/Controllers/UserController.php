<?php

namespace App\Http\Controllers;

use App\Http\Model\Addr;
use App\Http\Model\Agent;
use App\Http\Model\Cash;
use App\Http\Model\Citys;
use App\Http\Model\Config;
use App\Http\Model\Follower;
use App\Http\Model\Income;
use App\Http\Model\Users;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class UserController extends CommController
{
    public function register()
    {
        $input = Input::except('_token');        
        $phone = $input['phone'];
        $psw = $input['psw'];

        if (!$this->checkPhone($phone)){
            returnrtnMsg(1, '亲，请输入正确的号码！');
        }
        if (strlen($psw) < $this->pswMin() || strlen($psw) > $this->pswMax()){
            return rtnMsg(1, '密码长度最少'.$this->pswMin().'位最多'.$this->pswMax().'位!');
        }

        $count = Users::where('phone', $phone)->count();
        if (0 != $count){
            return rtnMsg(1, '该号码已经注册!');
        }

        $data = new Users;
        $data->phone = $phone;
        $data->psw = Crypt::encrypt($psw);
        if($data->save()) {
            (new Follower)->addRoot($data->id);

            return rtnMsg(0, '注册成功!');
        }
        else{
            return rtnMsg(1, '注册失败，请稍候再试!');
        }
    }

    public function logIn()
    {
        $input = Input::except('_token');
        $phone = $input['phone'];
        $psw = $input['psw'];

        if (!$this->checkPhone($phone)){
            return rtnMsg(1, '亲，请输入正确的号码！');
        }
        if (strlen($psw) < $this->pswMin() || strlen($psw) > $this->pswMax()){
            return rtnMsg(1, '密码长度最少'.$this->pswMin().'位最多'.$this->pswMax().'位!');
        }

        $user = Users::where('phone', $phone)->first();
        if (!$user || 0 == count($user)){
            return rtnMsg(1, '号码不存在!');
        }

        if ($user->errorcount >= 5) {
            if ((time() - $user->errortime) < 5 * 60) {
                return rtnMsg(1, '操作太频繁，请稍候再试！');
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

            return rtnMsg(1, '密码错误！');
        }

        session([FSessionNam=>$user]);
        $userBase = [
            'id'=>$user->id,
            'consume'=>$user->consume,
            'nickname'=>$user->nickname,
            'icon'=>$user->icon,
        ];

        return rtnMsg(0, $userBase);
    }

    public function logOut()
    {
        session([FSessionNam=>null]);

        return 0;
    }

    public function getUserBase()
    {
        $user = session(FSessionNam);
        $user = Users::select('id', 'consume', 'nickname', 'icon')->find($user['id']);
        return rtnMsg(0, $user);
    }

    public function getUserInfo()
    {
        $user = session(FSessionNam);
        $user = Users::find($user['id']);
        $user->psw = null;
        $user->qrc = null;

        return rtnMsg(0, $user);
    }

    public function updateWXInfo()
    {
        $user = session(FSessionNam);
        $user = Users::find($user['id']);
        $wxInfo = getWXUserInfo($user['unionid']);
        if ($wxInfo
            && property_exists($wxInfo, 'nickname')
            && property_exists($wxInfo, 'headimgurl')){

            $user['nickname'] = $wxInfo->nickname;
            $user['icon'] = saveIcon($wxInfo->headimgurl);
            if ($user->update()){
                $user->psw = null;
                return rtnMsg(0, $user);
            }else{
                return rtnMsg(1, '同步失败，请稍候再试！');
            }
        }

        return rtnMsg(1, '同步失败，请稍候再试！');
    }

    public function bindAccount()
    {
        $input = Input::except('_token');
        $phone = $input['phone'];
        $psw = $input['psw'];
        
        if (!$this->checkPhone($phone)){
            return rtnMsg(1, '亲，请输入正确的号码！');
        }
        if (strlen($psw) < $this->pswMin() || strlen($psw) > $this->pswMax()){
            return rtnMsg(1, '密码长度最少'.$this->pswMin().'位最多'.$this->pswMax().'位!');
        }

        $user = session(FSessionNam);
        $have = Users::where('phone', $phone)->count();
        if (0 != $have){
            return rtnMsg(1, '号码已经绑定!');
        }

        $input = [
            'phone'=>$phone,
            'psw'=>Crypt::encrypt($psw),
        ];
        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            $user = Users::find($user['id']);
            session([FSessionNam=>$user]);

            return rtnMsg(0, '绑定成功!');
        }else{
            return rtnMsg(1, '绑定失败，请稍候再试!');
        }
    }

    public function changePsw()
    {
        $input = Input::except('_token');
        $oldpsw = $input['old'];
        $newpsw = $input['new'];

        if (strlen($newpsw) < $this->pswMin() || strlen($newpsw) > $this->pswMax()){
            return rtnMsg(1, '密码长度最少'.$this->pswMin().'位最多'.$this->pswMax().'位!');
        }
        $user = session(FSessionNam);
        if (!$this->checkPhone($user['phone'])){
            return rtnMsg(1, '请先绑定号码!');
        }

        $user = Users::find($user['id']);
        if (Crypt::decrypt($user['psw']) != $oldpsw){
            return rtnMsg(1, '原密码验证失败!');
        }

        $input = [
            'psw'=>Crypt::encrypt($newpsw),
        ];
        $re = Users::where('id', $user['id'])->update($input);
        if ($re){
            return rtnMsg(0, '密码修改成功!');
        }else{
            return rtnMsg(1, '密码修改失败，请稍候再试!');
        }
    }

    public function changeUserInfo()
    {
        $input = json_decode(Input::get('data'));
        
        if (0 != strlen($input->email)){
            if (!$this->checkMail($input->email)){
                return rtnMsg(1, '请输入有效的有效地址!');
            }
        }
        if ($this->checkStr($input->name)
            || $this->checkStr($input->nickname)
            || $this->checkStr($input->qq)
            || $this->checkStr($input->weixnumber)){
            return rtnMsg(1, '请勿输入特殊字符!');
        }

        if (strlen($input->name) < 2 || strlen($input->name) > 64){
            return rtnMsg(1, '姓名最少2位最多64位!');
        }
        if (strlen($input->nickname) < 2 || strlen($input->nickname) > 64){
            return rtnMsg(1, '昵称最少2位最多64位!');
        }
        if (strlen($input->email) < 5 || strlen($input->email) > 64){
            return rtnMsg(1, '邮箱最少5位最多64位!');
        }
        if (!is_numeric($input->qq)){
            return rtnMsg(1, '请输入有效的QQ号码!');
        }
        if (strlen($input->qq) < 4 || strlen($input->qq) > 15){
            return rtnMsg(1, 'QQ号码最少4位最多15位!');
        }
        if (strlen($input->weixnumber) < 2 || strlen($input->weixnumber) > 64){
            return rtnMsg(1, '微信号最少2位最多64位!');
        }

        $user = session(FSessionNam);
        $nickCount = Users::where('nickname', $input->nickname)->where('id', '<>', $user['id'])->count();
        if (0 != $nickCount){
            return rtnMsg(1, '昵称重复啦!');
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
            return rtnMsg(0, '资料修改成功!');
        }else{
            return rtnMsg(1, '资料修改失败，请稍候再试!');
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

    public function saveAddr()
    {
        $input = json_decode(Input::get('data'));
        
        if (0 == strlen($input->name)){
            return rtnMsg(1, '请输入收货人姓名!');
        }
        if (0 == strlen($input->phone) || !$this->checkPhone($input->phone)){
            return rtnMsg(1, '请输入有效的收货人联系电话!');
        }
        if (0 == strlen($input->addr)){
            return rtnMsg(1, '请输入收货人联系地址!');
        }
        if (strlen($input->name) < 2 || strlen($input->name) > 64){
            return rtnMsg(1, '收货人姓名最少2位最多64位！');
        }
        if ($this->checkStr($input->name)){
            return rtnMsg(1, '请勿输入特殊字符!');
        }

        $user = session(FSessionNam);
        $input = [
            'userid'=>$user['id'],
            'name'=>$input->name,
            'phone'=>$input->phone,
            'addr'=>$input->addr,
        ];

        $re = Addr::create($input);
        if ($re){
            return rtnMsg(0, '保存收货地址成功!');
        }else{
            return rtnMsg(1, '保存收货地址失败，请稍候再试!');
        }
    }

    public function getAddr()
    {
        if (!$this->isLogIn()){
            return rtnMsg(errLogin(), '请先登录!');
        }

        $user = session(FSessionNam);
        $addr = Addr::where('userid', $user['id'])->get();

        return rtnMsg(0, $addr);
    }

    public function delAddr()
    {
        $id = Input::get('id');

        if (!is_numeric($id)){
            return rtnMsg(1, '参数错误!');
        }
        $user = session(FSessionNam);
        $re = Addr::where('id', $id)->delete();
        if($re){
            $addr = Addr::where('userid', $user['id'])->get();
            return rtnMsg(0, $addr);
        }else{
            return rtnMsg(1, '删除失败，请稍候再试!');
        }
    }

    public function agentShow()
    {
        $user = session(FSessionNam);
        $have = Agent::where('userid', $user['id'])->count();
        if (0 != $have){
            return rtnMsg(0, false);
        }else{
            return rtnMsg(0, true);
        }
    }
    
    public function agent()
    {
        $input = Input::except('_token');
        $phone = $input['phone'];
        $name = $input['name'];
        
        if (0 == strlen($name)){
            return rtnMsg(1, '请输入真实姓名!');
        }
        if (0 == strlen($phone) || !$this->checkPhone($phone)){
            return rtnMsg(1, '请输入联系电话!');
        }
        if ($this->checkStr($name)){
            return rtnMsg(1, '请勿输入特殊字符!');
        }
        if (strlen($name) < 2 || strlen($name) > 64){
            return rtnMsg(1, '姓名最少2位最多64位！');
        }

        $user = session(FSessionNam);
        $have = Agent::where('userid', $user['id'])->count();
        if (0 != $have){
            return rtnMsg(1, '你已经申请过了，不需要重新申请!');
        }

        $input = [
            'userid'=>$user['id'],
            'name'=>$name,
            'phone'=>$phone,
            'state'=>0,
        ];

        $re = Agent::create($input);
        if ($re){
            return rtnMsg(0, '申请成功!');
        }else{
            return rtnMsg(1, '申请失败，请稍候再试!');
        }
    }

    private function removeFile($name)
    {
        if(0 == strlen($name)){
            return;
        }

        $filePath = base_path().'/uploads/'.$name;
        if (file_exists($filePath)){
            unlink($filePath);
        }
    }

    private function getQRC($userID, $strQRC)
    {
        $expire_seconds = 2592000;
        $oneHour = 60 * 60;
        if (0 != strlen($strQRC)){
            $qrc = json_decode($strQRC);
            if ($qrc->time + $expire_seconds - $oneHour >= time()){
                return $qrc->qrc;
            }
            else{
                $this->removeFile($qrc->qrc);
            }
        }

        $param=[
            'expire_seconds'=>$expire_seconds,
            'action_name'=>'QR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>$userID
                ]
            ]
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.getToken();
        $result = https($url, json_encode($param));
        if (!$result
            || !property_exists($result, 'ticket')
            || !property_exists($result, 'url')){
            return '';
        }

        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($result->ticket);
        $qrcName = saveQRCPic($url);
        if(!$qrcName) {
            return '';
        }

        $user = Users::find($userID);
        $save = [
            'time'=>time(),
            'qrc'=>$qrcName
        ];
        $user->qrc = json_encode($save);
        $user->update();

        return $save['qrc'];
    }

    public function spreadInfo()
    {
        $user = session(FSessionNam);
        $userID = $user['id'];

        //是否显示
        $rtn = [];
        $config = Config::all()[0];
        $user = Users::select('consume', 'income', 'qrc')->find($userID);
        $rtn['Income'] = $user['income'];
        $rtn['Cash'] = $config['cash'];
        $rtn['follower'] = 0;
        $rtn['QRC'] = '';
        if ($user['consume'] >= $config['openspread']){
            $rtn['follower'] = (new Follower)->getFollowerCount($userID);
            $rtn['QRC'] = $this->getQRC($userID, $user['qrc']);
            $rtn['canShowQRC'] = true;
            return rtnMsg(0, $rtn);
        }else{
            $rtn['canShowQRC'] = false;
            return rtnMsg(0, $rtn);
        }
    }
    
    public function loadIncomeData($page)
    {
        if (!is_numeric($page)){
            return rtnMsg(1, '参数错误!');
        }

        $user = session(FSessionNam);
        $income = Income::where('userid', $user['id'])->
            skip($page * $this->numPerPage())->take($this->numPerPage())->
            orderBy('time','desc')->get();

        return rtnMsg(0, $income);
    }

    public function loadCashData($page)
    {
        if (!is_numeric($page)){
            return rtnMsg(1, '参数错误!');
        }

        $user = session(FSessionNam);
        $cash = Cash::where('userid', $user['id'])->
        skip($page * $this->numPerPage())->take($this->numPerPage())->
        orderBy('time','desc')->get();

        return rtnMsg(0, $cash);
    }

    public function cash()
    {
        $money = Input::get('money');

        if (!is_numeric($money)){
            return rtnMsg(1, '参数错误!');
        }
        $user = session(FSessionNam);
        $config = Config::all()[0];
        if ($config['cash'] > $money || 0 != $money % $config['cash']){
            return rtnMsg(1, '提现金额必须为'.$config['cash'].'的整数倍!');
        }
        $user = Users::find($user['id']);
        if ($money * 100 > $user['income']){
            return rtnMsg(1, '余额不足!');
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
                return rtnMsg(1, '提现申请失败，请稍候再试!');
            }

            return rtnMsg(0, '提现申请成功!');
        }
        else{
            return rtnMsg(1, '提现申请失败，请稍候再试!');
        }
    }

    public function showLevel($followerid)
    {
        if (!is_numeric($followerid)){
            return rtnMsg(1, '参数错误!');
        }

        $user = session(FSessionNam);
        $myLayer = Follower::where('userid', $user['id'])->first();
        $followerLayer = Follower::where('userid', $followerid)->first();

        return rtnMsg(0, $followerLayer['layer'] - $myLayer['layer']);
    }

    public function shareTo($shareID = 0)
    {
        return $shareID;
    }
}
