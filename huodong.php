<?php

$appid="wxe13660e9e2508d2a";
$appsecret="d7c9d3da42e21530342f0ed42caaddd9";
$redirect_uri="http://wx.xiaobugu.me/huodong.php";

//获取code
$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=123#wechat_redirect ";

if(!$_GET) {
	header('Location:'.$url);
	exit;
}


$code=$_GET['code'];

//获取网页版access_token和openid;
$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code ";


	$arr=https_request($url);
	var_dump($arr);
	function https_request($url,$data=""){
			 // 开启curl
			$ch = curl_init();
			//  设置传输选项
			//  设置传输地址
			curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false);
			curl_setopt($ch, CURLOPT_URL,$url);
			// 以文件流的形式返回
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			if ($data) {
				// 以post的方式
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			}

			//  发送curl
			$request = curl_exec($ch);
			//  关闭资源
			$tmpArr= json_decode($request,TRUE);

			if (is_array($tmpArr)) {
				return $tmpArr;
			}else{
				return $request;
			}
			curl_close($ch);
	}

?>