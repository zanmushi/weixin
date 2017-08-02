<?php 
$files = scandir('music');
$i = 1;
foreach ($files as $key =>$value) {
	if ($value !='.' && $value !='..'){
		echo $i.' '.$value."<br>";
		$i++;
	}
}