<?php

namespace App\Http\Controllers;

use App\http\Model\Config;
use App\http\Model\Follower;
use App\http\Model\Orders;
use App\http\Model\Users;
use App\http\Model\WXMsg;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class WXController extends CommController
{
    private function checkSignature($signature, $timestamp, $nonce)
    {
        $wx = getWXConfig();

        $token = $wx->Token;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1($tmpStr);

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function wxSVCheck($wxConfig){
        $input = Input::all();
        if (!array_key_exists('signature', $input)
            || !array_key_exists('timestamp', $input)
            || !array_key_exists('nonce', $input)
            || !array_key_exists('echostr', $input)){
            return;

        }

        $signature = $input['signature'];
        $timestamp = $input['timestamp'];
        $nonce = $input['nonce'];
        $echostr = $input['echostr'];
        if (!$this->checkSignature($signature, $timestamp, $nonce)) {
            return;
        }

        return $echostr;
    }    
    private function getWXPostEvent(){
        //获取通知的数据
        $xml = file_get_contents('php://input');
        if( empty($xml) ){
            return false;
        }

        $data = array();
        return xml_to_data($xml);
    }
    private function saveWXMsg($input)
    {
        $data = [
            'fromuser' => $input['FromUserName'],
            'touer' => $input['ToUserName'],
            'msgtype' => $input['MsgType'],
            'msg' => json_encode($input),
            'time' => $input['CreateTime']
        ];

        WXMsg::create($data);
    }
    private function toCSV($input)
    {
        $rtnMsg=[
            'ToUserName'=>$input['FromUserName'],
            'FromUserName'=>$input['ToUserName'],
            'CreateTime'=>time(),
            'MsgType'=>'transfer_customer_service',
        ];
        return data_to_xml($rtnMsg);
    }
    private function welCome($input){
        $wx = getWXConfig();
        $rtnMsg=[
            'ToUserName'=>$input['FromUserName'],
            'FromUserName'=>$input['ToUserName'],
            'CreateTime'=>time(),
            'MsgType'=>'text',
            'Content'=>$wx->welcome
        ];
        return data_to_xml($rtnMsg);
    }
    private function dowithWXMsg($input)
    {
        $rtnMsg = 'success';
        switch ($input['MsgType'])
        {
            case 'event':{
                $this->saveWXMsg($input);
                $event = $input['Event'];
                switch ($event){
                    case 'subscribe':
                        //关注事件
                        $user = Users::where('unionid', $input['FromUserName'])->first();
                        if (!$user){
                            $user = new Users;
                            $user->unionid = $input['FromUserName'];
                            if ($user->save()){
                                if (empty($input['EventKey'])){
                                    (new Follower)->addRoot($user->id);
                                }else{
                                    $spread_id = explode("_", $input['EventKey'])[1];
                                    $spreadUser = Users::find($spread_id);
                                    if($spreadUser){
                                        (new Follower)->addFollower($spread_id, $user->id);
                                    }
                                }
                            }
                        }
                        $rtnMsg = $this->welCome($input);
                        break;
                    default:
                        $rtnMsg = $this->toCSV($input);
                        break;
                    }
                }
                break;
            default:
                $rtnMsg = $this->toCSV($input);
                break;
        }

        return $rtnMsg;
    }

    //微信服务器验证,消息接收
    public function wxPost()
    {
        $wxConfig = getWXConfig();
        if (0 == $wxConfig->wxcheck){
            return $this->wxSVCheck($wxConfig);
        }

        $input = $this->getWXPostEvent();
        return $this->dowithWXMsg($input);
    }

    //python设置token
    public function setAccessToken($token, $sig)
    {
        $config = Config::first();
        $pyToken = json_decode($config['wx']);
        $mySig = md5($token.$pyToken->accessToken);
        if ($sig == $mySig){
            setToken($token);
            $jsTocken = getJSTicket();
            if (!$jsTocken){
                H_Log(LV_Error, 'get js token error.');
            }else{
                setJSToken($jsTocken);
            }
            return 0;
        }else{
            return 1;
        }
    }

    //订单支付结果回调
    public function wxPayNotify()
    {
        //获取数据
        $wechatAppPay = newWXPay();
        $result = $wechatAppPay->getNotifyData();
        if (!$result){
            return $wechatAppPay->replyNotify();
        }
        if ('SUCCESS' != $result['result_code']){
            return $wechatAppPay->replyNotify();
        }

        //参数验证
        $appid = $result['appid'];//公众号ID
        $mch_id = $result['mch_id'];//商户ID
        $openid = $result['openid'];//用户openID
        $transaction_id = $result['transaction_id'];//微信支付订单号
        $orderID = $result['out_trade_no'];//订单号
        $total_fee = $result['total_fee'];//订单金额
        $wx = getWXConfig();
        if ($wx->AppID != $appid
            || $wx->payID != $mch_id){
            return $wechatAppPay->replyNotify();
        }
        $order = Orders::where('id', $orderID)->where('status', 0)->first();
        if (!$order){
            return $wechatAppPay->replyNotify();
        }
        if ($order['price'] * 100 != $total_fee){
            return $wechatAppPay->replyNotify();
        }
        $user = Users::where('unionid', $openid)->first();
        if ($user['id'] != $order['userid']){
            return $wechatAppPay->replyNotify();
        }
        $payInfo = json_decode($order['payinfo']);
        if ($payInfo->prepay_id != $transaction_id){
            return $wechatAppPay->replyNotify();
        }

        //微信查询
        if (!wxQueryOrderPay($payInfo->prepay_id)){
            return $wechatAppPay->replyNotify();
        }

        $order->status = 1;
        if ($order->update()){
            $this->addIncome($user['id'], $orderID);
        }

        return $wechatAppPay->replyNotify();
    }
}
