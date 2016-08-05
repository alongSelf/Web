<?php

use App\http\Model\Users;
use App\http\Model\Config;
use App\http\Model\WXTemp;
use App\http\Model\Orders;
use App\http\Model\Follower;
use Illuminate\Support\Facades\Input;

require_once 'app/Common/WechatAppPay.class.php';

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

function setJSToken($token){
    $tmp = WXTemp::first();
    $tmp->wx_js_token = $token;
    $tmp->update();
}
function getJSToken(){
    $tmp = WXTemp::first();
    return $tmp['wx_js_token'];
}

function xml_to_data($xml){
    if(!$xml){
        return false;
    }
    //将XML转为array
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $data;
}
function data_to_xml( $params ){
    if(!is_array($params)|| count($params) <= 0)
    {
        return false;
    }
    $xml = "<xml>";
    foreach ($params as $key=>$val)
    {
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
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
        return false;
    }
}
//拉取用户信息
function getWXUserInfo($openID)
{
    if (!$openID || 0 == strlen($openID)){
        return false;
    }

    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.getToken().'&openid='.$openID.'&lang=zh_CN';
    return https($url);
}

//微信登录
function wxLogIn()
{
    $state = '';
    $input = Input::all();
    //微信登录
    if (array_key_exists('code', $input) && array_key_exists('state', $input)){
        $wx = getWXConfig();
        $state = $input['state'];
        //用state换取openid
        $openID = getWXOpenID($input['code'], $wx);
        if (0 != strlen($openID)){
            //是否已经存在
            $user = Users::where('unionid', $openID)->first();
            if ($user){
                //设置session
                if ('deficon.jpg' == $user['icon']){
                    $wxUserInfo = getWXUserInfo($openID);
                    if ($wxUserInfo
                        && property_exists($wxUserInfo, 'nickname')
                        && property_exists($wxUserInfo, 'headimgurl')){
                        $user->nickname = $wxUserInfo->nickname;
                        $user->icon = saveIcon($wxUserInfo->headimgurl);
                        $user->update();
                    }
                }
                session([FSessionNam=>$user]);
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

                $ses = session(FSessionNam);
                if ($ses){//已经登录直接绑定
                    $user = Users::find($ses['id']);
                    if (!$user){
                        if (Users::create($data)){
                            //设置session
                            $user = Users::where('unionid', $openID)->first();
                            session([FSessionNam=>$user]);
                            (new Follower)->addRoot($user['id']);
                        }
                    }else{
                        $user->update($data);
                    }
                }else{//未登录新建用户
                    if (Users::create($data)){
                        //设置session
                        $user = Users::where('unionid', $openID)->first();
                        session([FSessionNam=>$user]);
                        (new Follower)->addRoot($user['id']);
                    }
                }
            }
        }
    }

    return $state;
}

function newWXPay()
{
    $wx = getWXConfig();
    $options = array(
        'appid' 	=> 	$wx->AppID,		//填写微信分配的公众账号ID
        'mch_id'	=>	$wx->payID,				//填写微信支付分配的商户号
        'notify_url'=>	getUrl().'wxPayNotify',	//填写微信支付结果回调地址
        'key'		=>	$wx->payKey,				//填写微信商户支付密钥
        'mch_name'		=> $wx->mchName				//填写微信商名
    );

    return new wechatAppPay($options);
}

//创建订单
function wxCreateOrder($orderID, $price)
{
    $wechatAppPay = newWXPay();

    $params['body'] = '其他';						//商品描述
    $params['out_trade_no'] = $orderID;	//自定义的订单号
    $params['total_fee'] = $price * 100;					//订单金额 只能为整数 单位为分
    $params['trade_type'] = 'JSAPI';					//交易类型 JSAPI | NATIVE |APP | WAP

    $result = $wechatAppPay->unifiedOrder($params);

    return $result;
}

//查询订单
function wxQueryOrder($prepay_id)
{
    $wechatAppPay = newWXPay();

    return $wechatAppPay->orderQuery($prepay_id);
}

//查询是否支付成功
function wxQueryOrderPay($prepay_id)
{
    $result = wxQueryOrder($prepay_id);
    if (!$result){
        return false;
    }
    if ('SUCCESS' != $result['return_code']){
        return false;
    }
    if ('SUCCESS' != $result['result_code']){
        return false;
    }
    if ('SUCCESS' == $result['trade_state']){
        return true;
    }
    return false;
}

//关闭订单
function wxCloseOrder($prepay_id)
{
    $wechatAppPay = newWXPay();

    return $wechatAppPay->closeOrder($prepay_id);
}

//客户端支付参数
function wxAppPayParams($prepay_id)
{
    $wechatAppPay = newWXPay();
    
    return $wechatAppPay->getAppPayParams($prepay_id);
}

//保存二维码
function saveQRCPic($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER,0);
    curl_setopt($curl, CURLOPT_NOBODY,0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    $file = curl_exec($curl);
    if(curl_error($curl)){
        curl_close($curl);
        return false;
    }

    curl_close($curl);

    // 将文件写入获得的数据
    $newName = uniqid().'.jpg';
    $filename = base_path().'/uploads/'.$newName;

    $write = @fopen($filename, "w");
    if (!$write) {
        return false;
    }
    if (!fwrite($write, $file )) {
        fclose ($write);
        return false;
    }

    fclose ($write);

    return $newName;
}

//获取jsapi_ticket
function getJSTicket()
{
    $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.getToken().'&type=jsapi';

    $data = https($url);
    if ($data && 0 == $data->errcode){
        return $data->ticket;
    }else{
        return false;
    }
}

//创建菜单
function wxCreateMenu($data)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.getToken();
    $result = https($url, $data);
    if (!$result){
        return false;
    }

    return $result;
}
