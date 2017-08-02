<?php 
include "../weChat.class.php";

define('TOKEN','shisan');

$weChat = new weChat('wxe13660e9e2508d2a','d7c9d3da42e21530342f0ed42caaddd9');

$appid="wxe13660e9e2508d2a";
$timestamp=time();
$noncestr="1212121212";

// 获取ticke
$url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$weChat->get_access_token()}&type=jsapi";

$ticketArr=$weChat->https_request($url);

$ticket=$ticketArr['ticket'];

// 拼接字符串
$str="jsapi_ticket={$ticket}&noncestr={$noncestr}&timestamp={$timestamp}&url=http://wx.xiaobugu.me/jssdk/jssdk.php";

$signature=sha1($str);


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
</head>
<body>
	<button id="a">选择图片</button>
</body>
<script>
		wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?php echo $appid?>', // 必填，公众号的唯一标识
            timestamp: <?php echo timestamp()?>, // 必填，生成签名的时间戳
            nonceStr: '<?php echo $noncestr?>', // 必填，生成签名的随机串
            signature: '<?php echo $signature?>',// 必填，签名，见附录1
            jsApiList: ['chooseImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.ready(function(){
     
          });
        var tu=document.getElementById('a');
        tu.onclick=function tupian(){

                var images = {
                    localId:[],
                    serverId:[]
                }
                wx.chooseImage({
                 success: function (res) {
                    images.localId = res.localIds;
                    var imgs=images.localId;
                    for (var i = imgs.localId - 1; i>=0;i--){
                        str+='<img src="'+imgs[i]+'"><br>'
                    }

                    main.innerHTML=str;
             }
        });
     }
</script>
</html>