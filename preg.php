<?php 

$str = "sq上海";

preg_match("/^sq([\x{4e00}-\x{9fa5}]+)/ui", $str,$res);

var_dump($res);

?>