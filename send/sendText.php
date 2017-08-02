<?php 

if($_POST){
	include "../weChat.class.php";

	$weChat=new weChat('wxe13660e9e2508d2a','d7c9d3da42e21530342f0ed42caaddd9');
	$arr=$weChat->sendText(urlencode($_POST['text']));

	var_dump($arr);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Document</title>
</head>
<body>
	<form action="" method="post" >
		<p><input type="text" name="text" ></p>
		<p><input type="submit" value="群发" ></p>
	</form>
</body>
</html>