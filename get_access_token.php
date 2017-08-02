<?php 

include "./weChat.class.php";

define('TOKEN','shisan');

$weChat = new weChat('wxe13660e9e2508d2a','d7c9d3da42e21530342f0ed42caaddd9');
echo $weChat->get_access_token();

 ?>