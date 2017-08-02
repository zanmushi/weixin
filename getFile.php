<?php 

include "./weChat.class.php";



$weChat = new weChat('wxe13660e9e2508d2a','d7c9d3da42e21530342f0ed42caaddd9');
$arr=$weChat->getFile('vntb0BeBVdoc-ovC6E0MZTorUdq18IkzmCnoKxVLOj73rqBhoN_N4A6n_SpWI0SP');
var_dump($arr);




 ?>