<?php 
include "./weChat.class.php";

define('TOKEN','shisan');
$noncestr="Wm3WZYTPz0wzccnW";
$time=time();
$weChat = new weChat('wxe13660e9e2508d2a','d7c9d3da42e21530342f0ed42caaddd9');

// 获取ticket
$url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$weChat->get_access_token()."&type=jsapi";
$arr=$weChat->https_request($url);

$ticket=$arr['ticket'];

$str="jsapi_ticket={$ticket}&noncestr={$noncestr}&timestamp={$time}&url=http://wx.xiaobugu.me/index.php";
$str=sha1($str);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
</head>
<body>
	<button id="img">图片</button>
</body>
<script>
	wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: 'wxe13660e9e2508d2a', // 必填，公众号的唯一标识
    timestamp: <?php echo time()?>, // 必填，生成签名的时间戳
    nonceStr: '<?php echo $noncestr?>', // 必填，生成签名的随机串
    signature: '<?php echo $str?>',// 必填，签名，见附录1
    jsApiList: ['chooseImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
	wx.ready(function(){
		imgs=document.getElementById('img');
		imgs.onclick=function(){
				wx.chooseImage({
		    	success: function (res) {
		        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
		    }
		});
	}
});
</script>
</html>