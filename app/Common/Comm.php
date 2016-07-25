<?php

use App\http\Model\Config;

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
    if (!$result){
        return [];
    }

    return json_decode($result)->Traces;
}
