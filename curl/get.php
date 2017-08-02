<?php 
// 开启curl
$ch = curl_init();
//  设置传输选项
curl_setopt($ch, CURLOPT_URL,'http://www.baidu.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//  发送curl
$arr = curl_exec($ch);
//  关闭资源
echo  $arr;

curl_close($ch);