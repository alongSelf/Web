<?php
/**
 * 微信支付服务器端下单
 * @author		yc	 <yincaox@gmail.com>
 * 微信APP支付文档地址:  https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=8_6
 * 使用示例
 *	$options = array(
 *		'appid' 	=> 	'wx8888888888888888',		//填写微信分配的公众账号ID
 *		'mch_id'	=>	'1900000109',				//填写微信支付分配的商户号
 *		'notify_url'=>	'http://www.baidu.com/',	//填写微信支付结果回调地址
 *		'key'		=>	'5K8264ILTKC'				//填写微信商户支付密钥
 *	);
 *	统一下单方法
 *	$WechatAppPay = new wechatAppPay($options);
 *	$params['body'] = '商品描述';						//商品描述
 *	$params['out_trade_no'] = '1217752501201407';	//自定义的订单号
 *	$params['total_fee'] = '100';					//订单金额 只能为整数 单位为分
 *	$params['trade_type'] = 'APP';					//交易类型 JSAPI | NATIVE |APP | WAP 
 *	$wechatAppPay->unifiedOrder( $params );
 */
class wechatAppPay
{	
	//接口API URL前缀 https://api.mch.weixin.qq.com/sandbox
	const API_URL_PREFIX = 'https://api.mch.weixin.qq.com';
	//下单地址URL
	const UNIFIEDORDER_URL = "/pay/unifiedorder";
	//查询订单URL
	const ORDERQUERY_URL = "/pay/orderquery";
	//关闭订单URL
	const CLOSEORDER_URL = "/pay/closeorder";

	private $openid;
	//公众账号ID
	private $appid;
	//商户号
	private $mch_id;
	//商户名
	private $mch_name;
	//随机字符串
	private $nonce_str;
	//签名
	private $sign;
	//商品描述
	private $body;
	//商户订单号
	private $out_trade_no;
	//支付总金额
	private $total_fee;
	//终端IP
	private	$spbill_create_ip;
	//支付结果回调通知地址
	private $notify_url;
	//交易类型
	private $trade_type;
	//支付密钥
	private	$key;
	//证书路径
	private $public_key;
	private	$private_key;
	private $rootca;
	
	//所有参数
	private $params = array();
	
	public function __construct($options)
	{
		$this->appid = isset($options['appid'])?$options['appid']:'';
		$this->mch_id = isset($options['mch_id'])?$options['mch_id']:'';
		$this->mch_name = isset($options['mch_name'])?$options['mch_name']:'';
		$this->notify_url = isset($options['notify_url'])?$options['notify_url']:'';
		$this->key = isset($options['key'])?$options['key']:'';

		$this->public_key = isset($options['public_key'])?$options['public_key']:'';
		$this->private_key = isset($options['private_key'])?$options['private_key']:'';
		$this->rootca = isset($options['rootca'])?$options['rootca']:'';
	}
	
	/**
	 * 下单方法
	 * @param	$params	下单参数
	 */
	public function unifiedOrder($params){
		$this->body = $params['body'];
		$this->out_trade_no = $params['out_trade_no'];
		$this->total_fee = $params['total_fee'];
		$this->trade_type = $params['trade_type'];
		$this->nonce_str = genRandomString();
		$this->spbill_create_ip = $_SERVER['REMOTE_ADDR'];
		$this->openid = $params['openid'];

		$this->params = array();
		$this->params['appid'] = $this->appid;
		$this->params['mch_id'] = $this->mch_id;
		$this->params['nonce_str'] = $this->nonce_str;
		$this->params['body'] = $this->body;
		$this->params['out_trade_no'] = $this->out_trade_no;
		$this->params['total_fee'] = $this->total_fee;
		$this->params['spbill_create_ip'] = $this->spbill_create_ip;
		$this->params['notify_url'] = $this->notify_url;
		$this->params['trade_type'] = $this->trade_type;
		$this->params['limit_pay'] = 'no_credit';
		$this->params['openid'] = $this->openid;
		
		//获取签名数据
		$this->sign = $this->MakeSign($this->params);
		$this->params['sign'] = $this->sign;
		$xml = data_to_xml($this->params);
		$response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::UNIFIEDORDER_URL);
		if(!$response){
			return false;
		}

		H_PayLog('unifiedOrder', $xml, $response);
		$result = xml_to_data($response);
		if ($result['sign'] != $this->MakeSign($result)){
			H_Log(LV_Waring, 'unified order check sign error.');
			return false;
		}

		if(!empty($result['result_code']) && !empty($result['err_code'])){
			$result['err_msg'] = $this->error_code( $result['err_code'] );
		}

		return $result;
	}
	
	/**
	 * 查询订单信息
	 * @param $out_trade_no		订单号
	 * @return array
	 */
	public function orderQuery($out_trade_no){
		$this->params = array();
		$this->params['appid'] = $this->appid;
		$this->params['mch_id'] = $this->mch_id;
		$this->params['nonce_str'] = genRandomString();
		$this->params['out_trade_no'] = $out_trade_no;
		
		//获取签名数据
		$this->sign = $this->MakeSign($this->params);
		$this->params['sign'] = $this->sign;
		$xml = data_to_xml($this->params);
		$response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::ORDERQUERY_URL);
		if(!$response){
			return false;
		}

		H_PayLog('orderQuery', $xml, $response);
		$result = xml_to_data($response);
		if ($result['sign'] != $this->MakeSign($result)){
			H_Log(LV_Waring, 'query order check sign error.');
			return false;
		}

		if(!empty($result['result_code']) && !empty($result['err_code'])){
			$result['err_msg'] = $this->error_code( $result['err_code'] );
		}

		return $result;
	}
	
	/**
	 * 关闭订单
	 * @param $out_trade_no		订单号
	 * @return array
	 */
	public function closeOrder($out_trade_no){
		$this->params = array();
		$this->params['appid'] = $this->appid;
		$this->params['mch_id'] = $this->mch_id;
		$this->params['nonce_str'] = genRandomString();
		$this->params['out_trade_no'] = $out_trade_no;
		
		//获取签名数据
		$this->sign = $this->MakeSign($this->params);
		$this->params['sign'] = $this->sign;
		$xml = data_to_xml($this->params);
		$response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::CLOSEORDER_URL);
		if(!$response){
			return false;
		}

		H_PayLog('closeOrder', $xml, $response);
		$result = xml_to_data($response);
		if ($result['sign'] != $this->MakeSign($result)){
			H_Log(LV_Waring, 'close order check sign error.');
			return false;
		}

		return $result;
	}
	
	/**
 	 * 
 	 * 获取支付结果通知数据
	 * return array
 	 */
	public function getNotifyData(){
		//获取通知的数据
		$xml = file_get_contents('php://input');
		if(empty($xml)){
			return false;
		}

		H_PayLog('getNotifyData', '', $xml);
		$data = array();
		$data = xml_to_data($xml);
		if ($data['sign'] != $this->MakeSign($data)){
			H_Log(LV_Waring, 'pay notify check sign error.');
			return false;
		}

		if(!empty($data['return_code'])){
			if( $data['return_code'] != 'SUCCESS' ){
				return false;
			}
		}

		return $data;
	}
	
	/**
	 * 接收通知成功后应答输出XML数据
	 * @param string $xml
	 * return xml
	 */
	public function replyNotify(){
		$data['return_code'] = 'SUCCESS';
		$data['return_msg'] = 'OK';
		return data_to_xml($data);
	}
	
	 /**
	  * 生成APP端支付参数
	  * @param	$prepayid	预支付id
	  * return arry
	  */
	 public function getAppPayParams($prepayid){
		 $data['appId'] = $this->appid;
		 $data['timeStamp'] = time();
		 $data['nonceStr'] = genRandomString();
	 	 $data['package'] = 'prepay_id='.$prepayid;
		 $data['signType'] = 'MD5';
		 $data['paySign'] = $this->MakeSign($data);
		 return $data;
	 }

	/**
	 * 红包
	 * @param	$mch_billno	商户订单号 $re_openid 用户openid  $total_amount 付款金额
	 *  @return
	 */
	public function redPack($mch_billno, $re_openid, $total_amount){
		$this->params = array();
		$this->params['nonce_str'] = genRandomString();
		$this->params['mch_billno'] = $mch_billno;
		$this->params['mch_id'] = $this->mch_id;
		$this->params['wxappid'] = $this->appid;
		$this->params['send_name'] = $this->mch_name;
		$this->params['re_openid'] = $re_openid;
		$this->params['total_amount'] = $total_amount;
		$this->params['total_num'] = 1;
		$this->params['wishing'] = '恭喜发财！';
		$this->params['client_ip'] = $_SERVER["SERVER_ADDR"];
		$this->params['act_name'] = '我来推广';
		$this->params['remark'] = '粉丝越多，挣的越多！';

		$this->sign = $this->MakeSign($this->params);
		$this->params['sign'] = $this->sign;
		$xml = data_to_xml($this->params);
		$response = $this->postXmlCurl($xml, 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack', true);
		if(!$response){
			return false;
		}

		H_PayLog('redPack', $xml, $response);
		$result = xml_to_data($response);
		if ($result['sign'] != $this->MakeSign($result)){
			H_Log(LV_Waring, 'red pack check sign error.');
			return false;
		}

		return $result;
	}

	/**
	 * 生成签名
	 *  @return 签名
	 */
	public function MakeSign( $params ){
		//签名步骤一：按字典序排序数组参数
		ksort($params);
		$string = $this->ToUrlParams($params);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".$this->key;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
	
	/**
	 * 将参数拼接为url: key=value&key=value
	 * @param	$params
	 * @return	string
	 */
	public function ToUrlParams($params){
		$string = '';
		if(!empty($params)){
			$array = array();
			foreach($params as $key => $value){
				if (!empty($value) && $key != 'sign'){
					$array[] = $key.'='.$value;
				}
			}
			$string = implode("&",$array);
		}
		return $string;
	}
	
	/**
	 * 以post方式提交xml到对应的接口url
	 * 
	 * @param string $xml  需要post的xml数据
	 * @param string $url  url
	 * @param bool $useCert 是否需要证书，默认不需要
	 * @param int $second   url执行超时时间，默认30s
	 * @throws WxPayException
	 */
	private function postXmlCurl($xml, $url, $useCert = false, $second = 30){		
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
		if($useCert){
			//设置证书
			//PHP开发环境请使用商户证书文件apiclient_cert.pem和apiclient_key.pem ，rootca.pem是CA证书。
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, base_path().'/resource/scert/'.$this->public_key);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, base_path().'/resource/scert/'.$this->private_key);
			curl_setopt($ch,CURLOPT_CAINFO, base_path().'/resource/scert/'.$this->rootca);
		}
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return false;
		}
	}
	
	/**
	  * 错误代码
	  * @param	$code		服务器输出的错误代码
	  * return string
	  */
	 public function error_code($code){
		 $errList = array(
			'NOAUTH'				=>	'商户未开通此接口权限',
			'NOTENOUGH'				=>	'用户帐号余额不足',
			'ORDERNOTEXIST'			=>	'订单号不存在',
			'ORDERPAID'				=>	'商户订单已支付，无需重复操作',
			'ORDERCLOSED'			=>	'当前订单已关闭，无法支付',
			'SYSTEMERROR'			=>	'系统错误!系统超时',
			'APPID_NOT_EXIST'		=>	'参数中缺少APPID',
			'MCHID_NOT_EXIST'		=>	'参数中缺少MCHID',
			'APPID_MCHID_NOT_MATCH'	=>	'appid和mch_id不匹配',
			'LACK_PARAMS'			=>	'缺少必要的请求参数',
			'OUT_TRADE_NO_USED'		=>	'同一笔交易不能多次提交',
			'SIGNERROR'				=>	'参数签名结果不正确',
			'XML_FORMAT_ERROR'		=>	'XML格式错误',
			'REQUIRE_POST_METHOD'	=>	'未使用post传递参数 ',
			'POST_DATA_EMPTY'		=>	'post数据不能为空',
			'NOT_UTF8'				=>	'未使用指定编码格式',
		 );	
		 if(array_key_exists($code , $errList)){
		 	return $errList[$code];
		 }
	 }

	public function close_error($code){
		$errList = array(
			'ORDERPAID'				=>	'订单已支付',
			'SYSTEMERROR'				=>	'系统错误',
			'ORDERNOTEXIST'			=>	'订单不存在',
			'ORDERCLOSED'				=>	'订单已关闭',
			'SIGNERROR'			=>	'签名错误',
			'REQUIRE_POST_METHOD'			=>	'请使用post方法',
			'XML_FORMAT_ERROR'		=>	'XML格式错误',
			'MCHID_NOT_EXIST'		=>	'参数中缺少MCHID'
		);
		if(array_key_exists($code , $errList)){
			return $errList[$code];
		}
	}
}