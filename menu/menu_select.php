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

//查看菜单
$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$access_token}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$str = curl_exec($ch);
$menu_Arr = json_decode($str,true);
var_dump($menu_Arr);
curl_close($ch);


?>