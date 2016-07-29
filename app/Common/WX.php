<?php

use App\http\Model\Users;
use App\http\Model\Config;
use App\http\Model\WXTemp;
use Illuminate\Support\Facades\Input;

//微信
function getWXConfig()
{
    $config = Config::select('wx')->first();
    return json_decode($config['wx']);
}

function setToken($token){
    $tmp = WXTemp::first();
    $tmp->wx_access_token = $token;
    $tmp->update();
}

function getToken(){
    $tmp = WXTemp::first();
    return $tmp['wx_access_token'];
}

//通过code换取网页授权access_token
function getWXOpenID($code, $wx)
{
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wx->AppID.
        '&secret='.$wx->AppSecret.'&code='.$code.'&grant_type=authorization_code';

    $data = https($url);
    if ($data && property_exists($data, 'openid')){
        return $data->openid;
    }else{
        return '';
    }
}
//拉取用户信息
function getWXUserInfo($openID)
{
    if (!$openID || 0 == strlen($openID)){
        return null;
    }

    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.getToken().'&openid='.$openID.'&lang=zh_CN';
    return https($url);
}

//微信登录
function wxLogIn()
{
    $input = Input::all();
    //微信登录
    if (array_key_exists('code', $input) && array_key_exists('state', $input)){
         $wx = getWXConfig();
        if ($input['state'] == $wx->state){
            //用state换取openid
            $openID = getWXOpenID($input['code'], $wx);
            if (0 != strlen($openID)){
                //是否已经存在
                $user = Users::where('unionid', $openID)->first();
                if ($user){
                    //设置session
                    session(['user'=>$user]);
                }else{
                    $data['unionid'] = $openID;
                    
                    //拉去用户信息
                    $wxUserInfo = getWXUserInfo($openID);
                    if ($wxUserInfo
                        && property_exists($wxUserInfo, 'nickname')
                        && property_exists($wxUserInfo, 'headimgurl')){

                        $data['nickname'] = $wxUserInfo->nickname;
                        $data['icon'] = saveIcon($wxUserInfo->headimgurl);
                    }

                    $ses = session('user');
                    if ($ses){//已经登录直接绑定
                        $user = Users::find($ses['id']);
                        $user->update($data);
                    }else{//未登录新建用户
                        if (Users::create($data)){
                            //设置session
                            $user = Users::where('unionid', $openID)->first();
                            session(['user'=>$user]);
                        }
                    }
                }
            }
        }
    }
}

//创建订单
function wxCreateOrder($orderID, $price)
{
    $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    $wx = getWXConfig();
}
