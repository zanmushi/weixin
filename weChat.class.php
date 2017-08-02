<?php 
class weChat{
	public $appid;
	public $appsecret;

	public function __construct($appid,$appsecret){
		$this->appid=$appid;
		$this->appsecret=$appsecret;
		$conn = mysql_connect('localhost','weixin','liu123456');

		mysql_query('use weixin',$conn);
		mysql_query('set names utf8', $conn);
	}
	//验证消息的真实性
	
	public function valid(){
		$echostr = $_GET['echostr'];
		if ($this->checkSignature()){
			echo "$echostr";
		}else{
			echo "error";
			exit;
		}
	}
	//检验签名
	public function checkSignature(){
		//用get接收微信服务器携带的参数
		$signature = $_GET['signature'];
		$timestamp	= $_GET['timestamp'];
		$nonce	= $_GET['nonce'];

		//将token timestasmp nonce三个参数进行字典序排序
		$arr = array(TOKEN,$timestamp,$nonce);
		sort($arr,SORT_STRING);

		//将三个参数字符串拼接成一个字符串进行sha1加密
		$arr = join($arr);
		$arr = sha1($arr);

		//开发者将获得加密后的字符串可与signative对比,标识该请求者源于微信
		if ($arr == $signature){
			return true;
		}else{
			return false;
		}

	}

	//处理用户请求的消息
	
	public function responseMsg(){
		//接收原生XMl字符串
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if(!$postStr) {
			echo "post data error";
			exit;
		}
		//把原生字符串转化成对象
		$postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
		// 接收消息的类型
		$MsgType = $postObj->MsgType;

		//处理消息
		$this->checkMsgType($postObj,$MsgType);
	}

	//处理消息类型
	
	public function checkMsgType($postObj,$MsgType){
		switch ($MsgType) {
			case 'text':
			//处理文本消息
			$this->receiveText($postObj);
			      break;
			case 'image':
			//处理文本消息
			$this->receiveImage($postObj);
			      break; 
			case 'voice':
			 //处理语音消息
			 $this->receiveVoice($postObj);
			 	  break; 
			case 'event':
				$Event=$postObj->Event;
				//处理事件
				$this->checkEvent($postObj,$Event);
				  break;
			default:
			    break;    
		}
	}

	//处理事件的方法
	public function checkEvent($postObj,$Event){
		switch($Event){
			case 'subscribe':
					$data=array(
					array(
						'Title'=>'欢迎来到杨渡人社区',
						'Description'=>'你的支持使我们万分的荣幸',
						'PicUrl'=>'http://wx.xiaobugu.me/img/6.jpg',
						'Url'=>'http://www.baidu.com',
						),
					array(
						'Title'=>'孩时的快乐,你还记得吗?',
						'Description'=>'分享小时候的快乐趣事',
						'PicUrl'=>'http://wx.xiaobugu.me/img/4.jpg',
						'Url'=>'http://www.baidu.com',
						),
					array(
						'Title'=>'一个人的故事',
						'Description'=>'每个人的故事都是一部艰辛史',
						'PicUrl'=>'http://wx.xiaobugu.me/img/5.jpg',
						'Url'=>'http://www.baidu.com',
						),
					);
				$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->get_access_token()."&openid=".$postObj->FromUserName."&lang=zh_CN";
				$userInfo = $this->https_request($url);
				$sql="insert into users values(null,'$userInfo[openid]','$userInfo[nickname]','$userInfo[sex]','$userInfo[city]','$userInfo[country]','$userInfo[province]','$userInfo[headimgurl]','$userInfo[subscribe_time]')";
				mysql_query($sql);
			    $this->replyNews($postObj,$data);
				break;
			case 'unsubscribe':
				$sql="delete from users where oprnid='$postObj->FromUserName'";
				break;
			case 'CLICK':
				$this->checkClick($postObj,$postObj->EventKey);
				break;
			case 'LOCATION':
				$sql="insert into precision values(null,'$postObj->FromUserName','$postObj->Latitude','$postObj->Longitude')";
				mysql_query($sql);
				break;
			default:
				break;
		}
	}

	//处理click
	public function checkClick($postObj,$EventKey) {
		switch ($EventKey) {
			case 'NEWS':
				# code...
				$data=array(
					array(
						'Title'=>'京东物流三问苏宁：不提升服务就是自绝于用户',
						'Description'=>'苏宁作为天天快递的大股东，斥巨资入股之后，为什么苏宁连自己的商品都不用天天快递来配送，是订单量太小还是不太放心？',
						'PicUrl'=>'http://wx.xiaobugu.me/img/6.jpg',
						'Url'=>'http://wx.xiaobugu.me/jssdk/jssdk.php',
						),
					array(
						'Title'=>'泪目！这款聊天APP可模拟与已故亲人对话',
						'Description'=>'　随着科技的飞速发展，这个看似充满科幻色彩的桥段已不再是天马行空的幻想。据法国《快报》报道，美国《纽约时报》记者詹姆斯?弗拉霍(James Vlahos)设计出了一款聊天程序，实现了与已故父亲的交流对话',
						'PicUrl'=>'http://wx.xiaobugu.me/img/4.jpg',
						'Url'=>'http://wx.xiaobugu.me/jssdk/jssdk_oop.php',
						),
					array(
						'Title'=>'同样在搜索框下搞事情，Google和百度完全是两码事',
						'Description'=>'几乎在同一时刻，百度和 Google 两大搜索巨头都对他们的搜索 App 进行了改版',
						'PicUrl'=>'http://wx.xiaobugu.me/img/5.jpg',
						'Url'=>'http://www.baidu.com',
						),
					);
			    $this->replyNews($postObj,$data);
				break;
			case 'ZAN':
				# code...
				$this->replyText($postObj,'感谢亲您的点赞');
				break;
			default:
				# code...
				break;
		}
	}
	// 获取access_token
	public function get_access_token(){
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
		$request = $this->https_request($url);
		return $request['access_token'];

	}

	// 模拟get请求和post请求
	public function https_request($url,$data=""){
			 // 开启curl
			$ch = curl_init();
			//  设置传输选项
			//  设置传输地址
			curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false);
			curl_setopt($ch, CURLOPT_URL,$url);
			// 以文件流的形式返回
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			if ($data) {
				// 以post的方式
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			}

			//  发送curl
			$request = curl_exec($ch);
			//  关闭资源
			$tmpArr= json_decode($request,TRUE);

			if (is_array($tmpArr)) {
				return $tmpArr;
			}else{
				return $request;
			}
			curl_close($ch);
	}

	//处理文本消息
	public function receiveText($postObj){
		$Content = $postObj->Content;
		switch ($Content) {
			case '点歌':
				$str = "欢迎来到开心点歌\n";
				$files = scandir('music');
				$i = 1;
				foreach ($files as $key =>$value) {
					if ($value !='.' && $value !='..'){
						$str.= $i.' '.$value."\n";
						$i++;
					}
				}
				$str.="请输入对应的编号试听歌曲\n";
				$this->replyText($postObj,$str);
				break;
			case '笑话':
				$this->replyText($postObj,'这个笑话真好笑');
				break;
			case '新闻':
				$data=array(
					array(
						'Title'=>'詹姆斯发视频心情沉重 疑似回应欧文离队言论',
						'Description'=>'北京时间7月23日，骑士当家球星勒布朗-詹姆斯在个人社交媒体上发布了一段视频，疑似在回应凯里-欧文关于不愿意再跟他一起打球并且想离开骑士的言论',
						'PicUrl'=>'http://wx.xiaobugu.me/img/1.jpg',
						'Url'=>'http://www.baidu.com',
						),
					array(
						'Title'=>'欧文去年夺冠后就萌生去意 他的心已不在骑士',
						'Description'=>'北京时间7月23日，据著名NBA记者布莱恩-温德霍斯特报道，早在去年骑士队夺得总冠军之后，凯里-欧文就考虑过提出交易请求',
						'PicUrl'=>'http://wx.xiaobugu.me/img/2.jpg',
						'Url'=>'http://www.baidu.com',
						),
					array(
						'Title'=>'詹欧让历史重演!OK分家再现 超巨组合都会崩?',
						'Description'=>'北京时间7月23日，据美媒体报道，凯里-欧文要求离开骑士队的消息让整个NBA都感到震惊。但是在一些联盟的知情人士看来，欧文和詹姆斯的分道扬镳是迟早的事情，就像当年的科比和奥尼尔那样',
						'PicUrl'=>'http://wx.xiaobugu.me/img/3.jpg',
						'Url'=>'http://www.baidu.com',
						),
					);
			    $this->replyNews($postObj,$data);
			    break;
			default:
				preg_match("/^sq([\x{4e00}-\x{9fa5}]+)/ui", $Content,$arr);
				if ($arr[0]) {
					$sql="insert into text values(null,'$postObj->FromUserName','$arr[1]',".time.")";
					mysql_query($sql);
				}
				if (preg_match('/^\d{1,2}$/', $Content)){
					$files = scandir('music');
					$i = 1;
					foreach ($files as $key =>$value) {
						if ($value !='.' && $value !='..'){
							if($Content == $i) {
								$data = array(
									'Title'=>$value,
									'Description'=>$value,
									'MusicUrl'=>"http://wx.xiaobugu.me/music/".$value,
									'HQMusicUrl'=>"http://wx.xiaobugu.me/music/".$value,
									);
								$this->replyMusic($postObj,$data);
							}
							$i++;
					}
				}
				}
			    break;	
		}
	}



	// 处理图片消息
	public function receiveImage($postObj){
		$MediaId = $postObj->MediaId;
		$this->replyImage($postObj,$MediaId);
	}

	// 处理语音消息
	public function receiveVoice($postObj){
		$Recognition = $postObj->Recognition;
		$url="http://www.tuling123.com/openapi/api?key=3c584261a67e454cb83ebfe3b95d7e16&info=".$Recognition;

		$tuArr=$this->https_request($url);
		switch ($tuArr['code']) {
			case '100000':
				$this->replyText($postObj,$tuArr['text']);
				break;
			case '200000':
				$this->replyText($postObj,"<a href='".$tuArr[url]."''>".$tuArr['text'].'</a>');
				break;
			default:
				# code...
				break;
		}
		return $this->replyText($postObj,$Recognition);
	}


	//回复文本消息
	public function replyText($postObj,$Content){
		$xml='<xml>
		 <ToUserName><![CDATA[%s]]></ToUserName>
		 <FromUserName><![CDATA[%s]]></FromUserName>
		 <CreateTime>%d</CreateTime>
		 <MsgType><![CDATA[text]]></MsgType>
		 <Content><![CDATA[%s]]></Content>
		 </xml>';
		 echo sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$Content);
	}

	// 回复图片
	public function replyImage($postObj,$MediaId){
		$xml = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%d</CreateTime>
				<MsgType><![CDATA[image]]></MsgType>
				<Image>
				<MediaId><![CDATA[%s]]></MediaId>
				</Image>
				</xml>";
	echo sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$MediaId);
	}

	// 回复音乐消息
	public function replyMusic($postObj,$data){
		$xml = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%d</CreateTime>
				<MsgType><![CDATA[music]]></MsgType>
				<Music>
				<Title><![CDATA[%s]]></Title>
				<Description><![CDATA[%s]]></Description>
				<MusicUrl><![CDATA[%s]]></MusicUrl>
				<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
				</Music>
				</xml>";
		echo sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time(),$Content,$data['Title'],$data['Description'],$data['MusicUrl'],$data['HQMusicUrl']);
	}

	// 回复图文消息
	
	public function replyNews($postObj,$data){
		foreach($data as $key => $value) {
			$str.="<item>
				<Title><![CDATA[".$value[Title]."]]></Title> 
				<Description><![CDATA[".$value[Description]."]]></Description>
				<PicUrl><![CDATA[".$value[PicUrl]."]]></PicUrl>
				<Url><![CDATA[".$value[Url]."]]></Url>
				</item>";
		}
		$xml="<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%d</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>".count($data)."</ArticleCount>
			<Articles>
			".$str."
			</Articles>
			</xml>";
			echo sprintf($xml,$postObj->FromUserName,$postObj->ToUserName,time());
	}


	// 创建菜单
	public function menu_create($data){
		$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$this->get_access_token()}";
		return $this->https_request($url,$data);

	}
	//  查询菜单
		public function menu_select($data){
			$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$this->get_access_token()}";
			return $this->https_request($url);
	}
	//  删除查单
		public function menu_del($data){
			$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$this->get_access_token()}";
			return $this->https_request($url);
	}

	//  群发文本消息
	public function sendText($text){
		// 准备url地址
		$url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this->get_access_token();
		// 准备数据
		$sql="select * from users";
		$res = mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$arr1[]=$row['openid'];
		}
		$arr=array(
				"touser"=>$arr1,
				"msgtype"=>"text",
				"text"=>array("content"=>urldecode($text)),
			);
		
		// 封装成json
		$json=json_encode($arr);
		// 发送请求
		return $result=$this->https_request($url,$json);
		
		}

		// 上传临时素材
		public function uploads(){
			$url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->get_access_token()."&type=image";
			// curl文件上传
			$filepath=dirname(__FILE__)."/img/12.jpg";
			$filedata=array("file" => "@".$filepath);

			return $this->https_request($url,$filedata);
		}


		//获取临时文件
		
		public function getFile($media_id){
			$url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->get_access_token()."&media_id=".$media_id;
			return $this->https_request($url);
		}


		// 获取ticket
		public function getTicket(){
			
			$url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$this->get_access_token()}&type=jsapi";

			$ticketArr=$this->https_request($url);

			return $ticket=$ticketArr['ticket'];
		}

		//获取随机数
		public function getNonceStr($length='10'){
			$str="effjhnjkdlfdjh124357845776JHHFGJFTFXTDKJhJHGY";
			$newStr='';
			for($i=0;$i<$length;$i++){
				$newStr.=$str[rand(0,strlen($str)-1)];
			}
			return $newStr;
		}

		// 生成jssdk的签名
		public function getSignature(){
			$ticket=$this->getTicket();
			$noncedtr=$this->getNonceStr();
			$time=time();
			$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$str="jsapi_ticket={$ticket}&noncestr={$noncedtr}&timestamp={$time}&url=$url";
			
			$signature=sha1($str);

			$arr=array(
				'appId'=> $this->appid, 
			    'timestamp'=> $time, 
			    'nonceStr'=> $noncedtr, 
			    'signature'=> $signature,

				);
			return $arr;
		}
}


