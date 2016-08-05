<?php

use App\http\Model\Users;
use App\http\Model\Config;
use App\http\Model\WXPayLog;
use Illuminate\Support\Facades\Input;

define("FSessionNam", "UjfSEfsA1tjIUE6hK1bmf6rAGMZLvFyR_user");
define("BSessionNam", "a5S6zu7fMadJkwtwFfyjJ807r21t6ZO0_admin_user");

function rtnMsg($code, $msg)
{
    return $data = [
        'status' => $code,
        'msg' => $msg,
    ];
}

function getMillisecond(){
    //获取毫秒的时间戳
    $time = explode ( " ", microtime () );
    $time = $time[1] . ($time[0] * 1000);
    $time2 = explode( ".", $time );
    $time = $time2[0];
    return $time;
}

/**
 * 产生一个指定长度的随机字符串,并返回给用户
 * @param type $len 产生字符串的长度
 * @return string 随机字符串
 */
function genRandomString($len = 32) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    // 将数组打乱
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

function errLogin()
{
    return 10000;
}

define("LV_Debug", "Debug");
define("LV_Info", "Info");
define("LV_Waring", "Waring");
define("LV_Error", "Error");

function H_Log($logLV, $strMsg){
    if (!env('APP_DEBUG')){
        return;
    }
    
    $msg = '['.date('Y-m-d H:i:s').']['.$logLV.']'.$strMsg.PHP_EOL;
    file_put_contents(base_path().'/storage/logs/log.txt', $msg, FILE_APPEND);
}

function H_PayLog($type, $send, $recv){
    $data = [
        'type'=>$type,
        'sendmsg'=>$send,
        'recvmsg'=>$recv,
        'time'=>time()
    ];

    WXPayLog::create($data);
}

function getUrl()
{
    $PHP_SELF=$_SERVER['PHP_SELF'];

    return 'http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF, 0, strrpos($PHP_SELF,'/')+1);
}

/**
 *  post提交数据
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据
 * @return url响应返回的html
 */
function sendPost($url, $datas) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

/**
 * 电商Sign签名生成
 * @param data 内容
 * @param appkey Appkey
 * @return DataSign签名
 */
function kn_encrypt($data, $appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}

function selectLogistics($ShipperCode, $LogisticCode, $orderID){
    $requestData = array();
    $requestData['OrderCode'] = $orderID;
    $requestData['ShipperCode'] = $ShipperCode;
    $requestData['LogisticCode'] = $LogisticCode;

    $requestData= json_encode($requestData);

    $config = Config::select('logistics')->first();
    $logisticsInfo = json_decode($config['logistics']);

    $datas = array(
        'EBusinessID' => $logisticsInfo->userID,
        'RequestType' => '1002',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2',
    );
    $datas['DataSign'] = kn_encrypt($requestData, $logisticsInfo->apiKey);

    $result = sendPost('http://api.kdniao.cc/api/exrecommend/', $datas);

    return json_decode($result);
}

function sendPost2($url, $datas) {
    $temps = array();
    foreach ($datas as $key => $value) {
        $temps[] = sprintf('%s=%s', $key, $value);
    }
    $post_data = implode('&', $temps);
    $url_info = parse_url($url);
    if($url_info['port']=='')
    {
        $url_info['port']=80;
    }

    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader.= "Host:" . $url_info['host'] . "\r\n";
    $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader.= "Connection:close\r\n\r\n";
    $httpheader.= $post_data;
    $fd = fsockopen($url_info['host'], $url_info['port']);
    fwrite($fd, $httpheader);
    $gets = "";
    while (!feof($fd)) {
        if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
            break;
        }
    }
    while (!feof($fd)) {
        $gets.= fread($fd, 128);
    }
    fclose($fd);

    return $gets;
}

//在线电子单
function submitEOrder($requestData){
    $config = Config::select('logistics')->first();
    $logisticsInfo = json_decode($config['logistics']);

    $datas = array(
        'EBusinessID' => $logisticsInfo->userID,
        'RequestType' => '1007',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2',
    );
    $datas['DataSign'] = kn_encrypt($requestData, $logisticsInfo->apiKey);

    //正式接口  http://api.kdniao.cc/api/EOrderService  测试接口 http://testapi.kdniao.cc:8081/api/EOrderService
    $result=sendPost2('http://api.kdniao.cc/api/EOrderService', $datas);

    return $result;
}

//保存头像并返回名称
function saveIcon($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $file = curl_exec($curl);
    if(curl_error($curl)){
        curl_close($curl);
        return 'deficon.jpg';
    }

    curl_close($curl);

    // 将文件写入获得的数据
    $newName = uniqid().'.jpg';
    $filename = base_path().'/uploads/'.$newName;

    $write = @fopen($filename, "w");
    if (!$write) {
        return 'deficon.jpg';
    }
    if (!fwrite($write, $file )) {
        fclose ($write);
        return 'deficon.jpg';
    }

    fclose ($write);

    return $newName;
}

function https($url, $param=null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    if ($param){
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
    }
    $data = curl_exec($curl);

    if(curl_error($curl)){
        curl_close($curl);
        return false;
    }

    curl_close($curl);

    return json_decode($data);
}
