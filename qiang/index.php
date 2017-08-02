<?php
$conn = mysql_connect('localhost','weixin','liu123456');

mysql_query('use weixin',$conn);
mysql_query('set names utf8', $conn);

$sql="select users.*,text.time,text.text from users,text where users.openid=text.openid order by time desc limit 5";


$res=mysql_query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		*{
			margin: 0 auto;
			padding: 0px;
		}
		body{
			background: url('3.jpg');
		}
		h2{
			text-align: center;
			color: #fff;
			line-height: 100px;
		}
		.main{
			width: 600px;
			height: 530px;
			margin-top: 10px;
			border: 5px solid #00f;
			border-radius: 5px;
		}
		ul li{
			list-style: none;
		}
		ul li .main_li{
			height: 100px;
			background-color: #aaf;
			border-radius: 10px;
			margin: 5px;
		}
		.left{
			width: 20%;
			float: left;
		}
		.right{
			width: 80%;
			float: left;
		}
	</style>
	<script src="./jquery-3.1.1.js"></script>
</head>
<body>
	<h2>微信上墙系统</h2>
	<div class="main">
		<ul id="ul">
		<?php
			while ($row=mysql_fetch_assoc($res)) {
				echo '<li>
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
		?>
			
		</ul>
		
	</div>
</body>
<script type="text/javascript">
	setInterval(function(){
		$.get('http://wx.xiaobugu.me/qiang/ajax.php',{},function(){
			$("#ul").html($data);
		});
	},3000);
</script>
</html>