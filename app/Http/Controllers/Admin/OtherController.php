<?php

namespace App\Http\Controllers\Admin;

use App\http\Model\Config;
use App\http\Model\Evaluates;
use App\http\Model\Notice;
use App\http\Model\ShipperCode;
use App\http\Model\WXMenu;
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

    public function changeOnlywx()
    {
        $input = Input::all();
        if ($input){
            $config = Config::find($input['id']);
            $config->onlywx = $input['onlywx'];
            $re = $config->update();
            if($re){
                $data = [
                    'status' => 0,
                    'msg' => '更新成功！',
                ];
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '更新失败，请稍后重试！',
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
        $config['contactus'] = json_decode($config['contactus']);
        return view('admin.other.contactus', compact('config'));
    }
    public function changeContactus()
    {
        $input = Input::except('_token');
        if ($input) {
            $config = Config::find($input['id']);

            $data['phone'] = $input['phone'];
            $data['email'] = $input['email'];
            $data['qq'] = $input['qq'];
            $data['postAddr'] = $input['postAddr'];
            $config->contactus = json_encode($data);
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
        $logisticsAddr = json_decode($config['logisticsaddr']);

        return view('admin.other.logistics', compact('id', 'logistics', 'logisticsAddr'));
    }
    public function setLogistics()
    {
        $input = Input::except('_token');
        $id = $input['id'];

        $logistics['userID'] = $input['userID'];
        $logistics['apiKey'] = $input['apiKey'];

        $logisticsAddr['name'] = $input['name'];
        $logisticsAddr['phone'] = $input['phone'];
        $logisticsAddr['province'] = $input['province'];
        $logisticsAddr['city'] = $input['city'];
        $logisticsAddr['county'] = $input['county'];
        $logisticsAddr['address'] = $input['address'];

        $config = Config::first();
        $config->logistics = json_encode($logistics);
        $config->logisticsaddr = json_encode($logisticsAddr);

        if ($config->update()) {
            return back()->with('errors', '更新成功！');
        } else {
            return back()->with('errors', '更新失败，请稍后重试！');
        }
    }

    public function showShippercode($name = '')
    {
        if (0 == strlen($name)){
            $data = ShipperCode::paginate(10);
        }else{
            $data = ShipperCode::where('name','like','%'.$name.'%')->paginate(10);
        }

        return view('admin.other.shippercode', compact('name', 'data'));
    }

    public function setShippercode()
    {
        $input = Input::except('_token');
        $id = $input['id'];
        $data = ShipperCode::find($id);
        if (!$data){
            return [
                'status' => 1,
                'msg' => '参数错误！',
            ];
        }

        if(1 == $data['display']){
            $data['display'] = 0;
        }else{
            $data['display'] = 1;
        }
        if ($data->update()){
            return [
                'status' => 0,
                'msg' => 'OK！',
            ];
        }else{
            return [
                'status' => 1,
                'msg' => '更新失败，请稍候再试！',
            ];
        }
    }
    
    public function showLAccount($id)
    {
        $shippercode = ShipperCode::find($id);
        $account = json_decode($shippercode['account']);

        return view('admin.other.logisticsaccount', compact('id', 'account'));
    }
    public function setLAccount()
    {
        $input = Input::except('_token');
        $account['CustomerName'] = $input['CustomerName'];
        $account['CustomerPwd'] = $input['CustomerPwd'];
        $account['SendSite'] = $input['SendSite'];

        $shippercode = ShipperCode::find($input['id']);
        $shippercode->account = json_encode($account);
        if($shippercode->update()){
            return back()->with('errors','更新成功！');
        }else{
            return back()->with('errors','更新失败，请稍后重试！');
        }
    }

    public function showWXSet()
    {
        $config = Config::first();
        $config['wx'] = json_decode($config['wx']);

        return view('admin.other.wx', compact('config'));
    }
    public function setWXSet()
    {
        $input = Input::except('_token');
        $wx['Token'] = $input['Token'];
        $wx['AppID'] = $input['AppID'];
        $wx['AppSecret'] = $input['AppSecret'];
        $wx['accessToken'] = $input['accessToken'];
        $wx['state'] = $input['state'];
        $wx['payID'] = $input['payID'];
        $wx['payKey'] = $input['payKey'];
        $wx['wxcheck'] = $input['wxcheck'];

        $config = Config::first();
        $config['wx'] = json_encode($wx);
        if($config->update()){
            return back()->with('errors','更新成功！');
        }else{
            return back()->with('errors','更新失败，请稍后重试！');
        }
    }

    public function wxMenu()
    {
        $menu = WXMenu::first();
        if (!$menu){
            $menu['menu'] = '';
            WXMenu::create($menu);
            $menu =  WXMenu::first();
        }

        return view('admin.other.wxmenu', compact('menu'));
    }

    public function createWXMenu()
    {
        $input = Input::except('_token');
        $rtn = wxCreateMenu($input['menu']);
        if (!$rtn){
            return back()->with('errors','菜单创建失败！');
        }
        if (0 != $rtn->errcode){
            return back()->with('errors',$rtn->errmsg);
        }

        $menu =  WXMenu::find($input['id']);
        if($menu->update($input)){
            return back()->with('errors','菜单创建成功！');
        }else{
            return back()->with('errors','菜单创建失败，请稍后重试！');
        }
    }

    public function wxCSV()
    {
        $errors = '';
        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.getToken();
        $csv = https($url);
        if (!$csv){
            $errors = '获取客服信息失败，请稍后再试！';
            return view('admin.other.wscsv', compact('csv', 'errors'));
        }
        if (property_exists($csv, 'errcode')){
            if ($csv['errcode'] != 0){
                $errors = $csv['errmsg'];
                $csv = array();
                return view('admin.other.wscsv', compact('csv', 'errors'));
            }
        }

        return view('admin.other.wscsv', compact('csv', 'errors'));
    }
}