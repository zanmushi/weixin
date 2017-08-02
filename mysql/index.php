<?php 

$conn = mysql_connect('localhost','weixin','liu123456');

mysql_query('use weixin',$conn);
mysql_query('set names utf8', $conn);

$sql="select * from user";

$res=mysql_query($sql);


while ($row=mysql_fetch_assoc($res)) {
	# code...
	var_dump($row);
	echo "<hr>";
}
?>