<?php 
$appid='wxe13660e9e2508d2a';
$appsecret='d7c9d3da42e21530342f0ed42caaddd9';
$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";

// 开启curl
$ch = curl_init();
//  设置传输选项
//  设置传输地址
curl_setopt($ch, CURLOPT_URL,$url);
// 以文件流的形式返回
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//  发送curl
$arr = curl_exec($ch);
//  关闭资源
$arrs = json_decode($arr,TRUE);
$access_token = $arrs['access_token'];
curl_close($ch);

$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
			$data='{
			"button":[
			{
			"name":"hello",
			"sub_button":[
			{
			"type":"click",
			"name":"新闻",
			"key":"name1"
			},
			{
			"type":"click",
			"name":"点歌",
			"key":"name2"
			}
			]
			},
			{
			"type":"view",
			"name":"菜单",
			"url":"http://www.baidu.com"
			}
			]
			}';


 // 开启curl
$ch = curl_init();
//  设置传输选项
//  设置传输地址
curl_setopt($ch, CURLOPT_URL,$url);
// 以文件流的形式返回
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 以post的方式
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
//  发送curl
$arr1 = curl_exec($ch);
//  关闭资源
$arrs1 = json_decode($arr1,TRUE);
var_dump($arrs1);
curl_close($ch);
?>