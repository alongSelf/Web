<?php

use App\http\Model\Users;
use App\http\Model\Config;
use Illuminate\Support\Facades\Input;

//微信
function getWXConfig()
{
    $config = Config::select('wx')->first();
    return json_decode($config['wx']);
}

function setToken($token){
    $GLOBALS['wx_access_token'] = $token;
}

function getToken(){
    if (array_key_exists('wx_access_token', $GLOBALS)){
        return $GLOBALS['wx_access_token'];
    }
    return '';
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
                    //拉去用户信息
                    $wxUserInfo = getWXUserInfo($openID);

                    //保存数据
                    $data['unionid'] = $openID;
                    $data['nickname'] = $wxUserInfo->nickname;
                    $data['icon'] = saveIcon($wxUserInfo->headimgurl);

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
