<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Config;
use App\http\Model\Evaluates;
use App\http\Model\Notice;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OtherController extends CommonController
{
    //
    public function configIndex()
    {
        $config = Config::first();
        return view('admin.other.config', compact('config'));
    }

    public function changeTitle()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->title = $input['title'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '软件名更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '软件名更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    public function changeAgent()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->agent = $input['agent'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '代理介绍更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '代理介绍更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    public function changeSpread()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->spread = $input['spread'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '推广介绍更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '推广介绍更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }
    public function changeOpenSpread()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->openspread = $input['openspread'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '推广条件更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '推广条件更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    public function changeCash()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->cash = $input['cash'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '提现条件更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '提现条件更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    public function changeCommission1()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->commission1 = $input['commission'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '一级提成更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '一级更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }
    public function changeCommission2()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->commission2 = $input['commission'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '二级提成更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '二级更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }
    public function changeCommission3()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->commission3 = $input['commission'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '三级提成更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '三级更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    public function noticeIndex()
    {
        $notice = Notice::first();
        return view('admin.other.notice', compact('notice'));
    }
    public function changeNotice()
    {
        $input = Input::all();
        if ($input){
            $notice = Notice::find($input['id']);
            $notice->notice = $input['notice'];
            $re = $notice->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '公告更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '公告更新失败，请稍后重试！',
                ];
            }
            return $data;
        }
    }

    public function evaluatesIndex()
    {
        $evaluates = Evaluates::orderBy('itemid')->paginate(10);
        return view('admin.other.evaluates', compact('evaluates'));
    }
    public function searchEvaluates($ev_id)
    {
        $evaluates = Evaluates::where('itemid', $ev_id)->orderBy('id','desc')->paginate(10);
        return view('admin.other.evaluates', compact('evaluates'));
    }
    public function delEvaluates()
    {
        $input = Input::except('_token');
        $re = Evaluates::where('id', $input['id'])->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '评论删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '评论删除失败，请稍后重试！',
            ];
        }
        return $data;
    }
    public function disPlayEvaluates()
    {
        $input = Input::except('_token');
        $evaluates = Evaluates::find($input['id']);
        if (0 != $evaluates['display']){
            $evaluates['display'] = 0;
        }else{
            $evaluates['display'] = 1;
        }
        $re = $evaluates->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '评论显示/隐藏成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '评论显示/隐藏失败，请稍后重试！',
            ];
        }
        return $data;
    }

    public function contactus()
    {
        $config = Config::first();
        return view('admin.other.contactus', compact('config'));
    }
    public function changeContactus()
    {
        $input = Input::except('_token');
        if ($input) {
            $config = Config::find($input['id']);
            $config->contactus = $input['contactus'];
            $re = $config->update();
            if ($re) {
                return back()->with('errors', '联系我们更新成功！');
            } else {
                return back()->with('errors', '联系我们更新失败，请稍后重试！');
            }
        }
    }

    public function showLogistics()
    {
        $config = Config::first();
        $logistics = json_decode($config['logistics']);
        $id = $config['id'];
        if ($logistics && $logistics->userID){
            $userID = $logistics->userID;
        }else{
            $userID = '';
        }
        if ($logistics && $logistics->apiKey){
            $apiKey = $logistics->apiKey;
        }else{
            $apiKey = '';
        }

        return view('admin.other.logistics', compact('id', 'userID', 'apiKey'));
    }
    public function setLogistics()
    {
        $input = Input::except('_token');
        $id = $input['id'];

        $logistics['userID'] = $input['userID'];
        $logistics['apiKey'] = $input['apiKey'];

        $config = Config::first();
        $config->logistics = json_encode($logistics);

        if ($config->update()) {
            return back()->with('errors', '更新成功！');
        } else {
            return back()->with('errors', '更新失败，请稍后重试！');
        }
    }

    public function showWXSet()
    {
        return view('admin.other.wx');
    }
    public function setWXSet()
    {

    }
}