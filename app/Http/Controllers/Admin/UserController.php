<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Agent;
use App\http\Model\Cash;
use App\http\Model\Follower;
use App\http\Model\Income;
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

    public function show($id)
    {
        $data = Users::find($id);
        return view('admin.user.show', compact('data'));
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

    public function agent($phone=null)
    {
        if ($phone){
            $data = Agent::where('phone', $phone)->orderBy('state')->paginate(10);
        }else{
            $data = Agent::orderBy('state')->paginate(10);
        }

        return view('admin.user.agent', compact('data', 'phone'));
    }

    public function changeAgent()
    {
        $userid = Input::get('userid');
        $data = Agent::find($userid);
        if ($data){
            $data->state = 1;
            if($data->update()){
                $rtn = [
                    'status' => 0,
                    'msg' => '处理成功！',
                ];
            }else{
                $rtn = [
                    'status' => 1,
                    'msg' => '处理失败，请稍后重试！',
                ];
            }

            return $rtn;
        }
    }

    public function cash()
    {
        $data = Cash::orderBy('status')->paginate(10);

        return view('admin.user.cash', compact('data'));
    }
    public function cashCancel()
    {
        $id = Input::get('id');
        $data = Cash::where('id', $id)->where('status', 0)->first();
        if (!$data){
            return [
                'status' => 1,
                'msg' => '处理失败，请稍后重试！',
            ];
        }

        $data->status = 2;
        if($data->update()){
            $user = Users::find($data['userid']);
            if ($user){
                $user->income = $user->income + $data['money'] * 100;
                $user->update();
            }
            return [
                'status' => 0,
                'msg' => '处理成功！',
            ];
        }else{
            return [
                'status' => 1,
                'msg' => '处理失败，请稍后重试！',
            ];
        }
    }
    public function cashPay()
    {
        $id = Input::get('id');
        $data = Cash::where('id', $id)->where('status', 0)->first();
        if (!$data){
            return [
                'status' => 1,
                'msg' => '处理失败，请稍后重试！',
            ];
        }
        $user = Users::find($data['userid']);
        if (!$user){
            return [
                'status' => 1,
                'msg' => '处理失败，请稍后重试！',
            ];
        }

        //微信红包。。。。。。

        $data->status = 1;
        if($data->update()){
            return [
                'status' => 0,
                'msg' => '处理成功！',
            ];
        }else{
            return [
                'status' => 1,
                'msg' => '红包发放成功，但该申请状态更新失败，请手动更改。',
            ];
        }
    }

    public function income($userID = null)
    {
        if ($userID){
            $data = Income::where('userid', $userID)->orderBy('time', 'desc')->paginate(10);
        }else{
            $data = Income::orderBy('time', 'desc')->paginate(10);
        }

        return view('admin.user.income', compact('data', 'userID'));
    }

    public function follower($condition, $userID = null)
    {
        $follower = new Follower;
        if (!$userID){
            $data = $follower->getRoot();

            return view('admin.user.follower', compact('data', 'userID', 'condition'));
        }

        $myLayer = $follower->getMy($userID);
        if ($myLayer){
            if ($condition == 'junior'){
                $data = $follower->getFollower($userID);
            }else{
                $data = $follower->getChief($userID);
            }
        }else{
            $data = Follower::where('id', 0)->paginate(10);
        }

        return view('admin.user.follower', compact('data', 'myLayer', 'userID', 'condition'));
    }
}
