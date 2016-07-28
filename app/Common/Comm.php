<?php

use App\http\Model\Users;
use App\http\Model\Config;
use Illuminate\Support\Facades\Input;

function rtnMsg($code, $msg)
{
    return $data = [
        'status' => $code,
        'msg' => $msg,
    ];
}

function errLogin()
{
    return 10000;
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
    $result=sendPost2('http://testapi.kdniao.cc:8081/api/EOrderService', $datas);

    return $result;
}

//保存图片并返回名称
function saveIcon($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $file = curl_exec($curl);
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

function https($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    $data = curl_exec($curl);

    if($error=curl_error($curl)){
        curl_close($curl);
        return null;
    }

    curl_close($curl);

    return json_decode($data);
}
