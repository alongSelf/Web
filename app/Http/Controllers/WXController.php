<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class WXController extends CommController
{
    private function checkSignature($signature, $timestamp, $nonce)
    {
        $wx = getWXConfig();

        $token = $wx['token'];
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

    //微信服务器验证
    public function WXSVCheck()
    {
        $input = Input::all();

        $signature = $input['signature'];
        $timestamp = $input['timestamp'];
        $nonce = $input['nonce'];
        $echostr = $input['echostr'];
        if (!$this->checkSignature($signature, $timestamp, $nonce)) {
            return;
        }

        return $echostr;
    }

    //python设置token
    public function setAccessToken($token, $sig)
    {
        $mySig = md5($token.'Lsy20130123$#');
        if ($sig == $mySig){
            setToken($token);
        }
    }
}
