<?php 
include '../weChat.class.php';
define('TOKEN','shisan');

$weChat = new weChat('wxe13660e9e2508d2a','d7c9d3da42e21530342f0ed42caaddd9');
$data='{
     "button":[
     {	
          "type":"click",
          "name":"新闻",
          "key":"NEWS"
      },
      {
           "name":"娱乐",
           "sub_button":[
           {	
               "type":"view",
               "name":"游戏",
               "url":"http://wx.xiaobugu.me/game/index.html"
            },
            {
                 "type":"view",
                 "name":"笑话",
                 "url":"http://v.qq.com/"
            }]
       },

       {	
          "type":"click",
          "name":"赞我们",
          "key":"ZAN"
      }]
 }';
 $arr = $weChat->menu_create($data);
 
 var_dump($arr);

 ?>