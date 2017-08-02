<?php
$conn = mysql_connect('localhost','weixin','liu123456');

mysql_query('use weixin',$conn);
mysql_query('set names utf8', $conn);

$sql="select users.*,text.time,text.text from users,text where users.openid=text.openid order by time desc limit 5";


$res=mysql_query($sql);

			while ($row=mysql_fetch_assoc($res)) {
				$str.='<li>
						<div class="main_li">
							<div class="left">
								<img src="'.$row[headimgurl].'" width="100%" height="100px" alt="">
							</div>
							<div class="right">
								<h2>'.$row[nickname].':'.$row[text].'</h2>
							</div>
						</div>
					</li>';			
				}
				echo $str;
?>