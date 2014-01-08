<?php

class autogm{

	private $player_id;
	private $account_code;

	public function init(){

		if(function_exists('curl_init')){

		}else{
			echo '未开启curl拓展';
		}
	}

	public function index(){

		echo 'come on';
	}

	
	private function autohttp($url){


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT,'5');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$output = curl_exec($ch);
		
		$info = curl_getinfo($ch);
		// print_r($info);
		// exit();
		if(curl_errno($ch)){	
			return 'error';//curl_error($ch)
		}else{
			if ($output === FALSE) {//空白页
				return false;
			}else{			//有内容
				return $output;
			}
		}	

		curl_close($ch);

	}

	private function Starthttp($url,$json){

		$ch = curl_init($url);                                                                        
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                       
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                        
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                            
			'Content-Type: application/json',                                                                                  
			'Content-Length: ' . strlen($json))                                                                         
		);   

		$result = curl_exec($ch);  
		if(curl_errno($ch)){	//超时无响应
			return 'error';//curl_error($ch)
		}else{
			if ($result === FALSE) {//空白页
				return false;
			}else{			//有内容
				return $result;
			}
		}
		curl_close($ch);
	}
	/**
	 * functionName: gm2000
	 * Description: 查询账号基本信息 2000
	 * Author: Kim
	 * Date: 2013-3-18 14:31:04
	 **/
	public function gm2000($args,$sip,$port,$loginName){	//queryMode=（账号(0) / ID(1) /精确昵称( (2)/    what=搜索的DD
		//$sip = "192.168.0.104";
		$ip = $this->getUserIp(); 
		$name = $loginName.rand(1,100);
		$code= 2000;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
		//http://192.168.0.104:20101/192.168.0.141&qgl&2000&{"queryMode":"1","what":"13"}
	}

	/**
	 * functionName: gm2001
	 * Description: 该用户的详细信息 2001 
	 * Author: Kim
	 * Date: 2013-3-18 14:31:04
	 **/
	public function gm2001($args,$sip,$port,$loginName,$domain){	//该用户的详细信息 
		
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 2001;
		$json = json_encode($args);
		
		if($sip == '183.60.41.227'){//227域名读以下路径
			$url = "http://csjk.aofyx.com/get_json.php?sip={$sip}&name=".urlencode($name)."&port={$port}&what=".urlencode($json)."&code=".$code;
		}else{//其他域名读以下路径
			$url = "http://{$domain}/TestLogin/get_json.php?sip={$sip}&name=".urlencode($name)."&port={$port}&what=".urlencode($json)."&code=".$code;
			// $url = "http://192.168.0.146:20101/127.0.0.1&qgl&2001&".urlencode($json);

		}
		// print_R($url);
		// echo "<hr/>";
		// echo "<hr/>";
		
		
		//echo $url;exit;
		return $this->autohttp($url);
		// $sip = "192.168.0.104";
		// $ip = $this->getUserIp();
		// $name = $loginName.rand(1,100);
		// $code= 2001;
		// print_r( $args);
		// exit;
		// $json = json_encode($args);
		// $url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		// echo $url;exit();
		// return $this->autohttp($url);
		//http://192.168.0.109:20101/192.168.0.141&qgl&2001&{"what":"13"}

	}

	/**
	 * functionName: gm2002
	 * Description:查询账号的 背包 信息 2001 
	 * Author: Kim
	 * Date: 2013-3-18 14:31:04
	 **/
	public function gm2002($args,$sip,$port,$loginName){	//查询账号的 背包 信息 
		//$sip = "192.168.0.104";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 2002;
		$json = json_encode($args);
 		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		//$url  = 'http://192.168.0.104:20101/192.168.0.141&qgl&2002&%7B%22what%22:%2213%22%7D';
		return $this->autohttp($url);
	
	}


	/**
	 * functionName: gm2003
	 * Description: 模糊查询 2003 
	 * Author: Kim
	 * Date: 2013-4-11 16:06:02
	 **/
	public function gm2003($args,$sip,$port,$loginName){	//what 什么 pageNum 分页
		//$sip = "192.168.0.104";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 2003;
		/*$sip = '183.60.41.227';
		$port = "20103";*/
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
		//http://192.168.0.109:20101/192.168.0.141&qgl&2003&{"pageNum":"1","what":"上官"}

	}
	
	/**
	 * functionName: gm1001
	 * Description: 禁言1001
	 * Author: Kim
	 * Date: 2013-4-11 16:06:02
	 **/
	public function gm1001($args,$sip,$port,$loginName){	//names:角色名	//禁言 GM管理员的账号 IP也是GM管理员所用的机器的IP
		//$sip = "192.168.0.109";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 1001;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/"  . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
		//http://192.168.0.109:20101/192.168.0.141&haha&1001&{"names":["123","456","789"],"time":3}
		//http://192.168.0.109:20101/192.168.0.141&haha&1001&{"names":["安坤气"],"time":30}
	}
	
	/**
	 * functionName: gm1002
	 * Description: 发布公告1002
	 * Author: Kim
	 * Date: 2013-4-11 16:06:02
	 **/
	public function gm1002($args,$sip,$port,$loginName){	//发送公告，时间为空时就是即时公告
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 1002;
		$json = json_encode($args);
		$str = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($str);
		//http://192.168.0.109:20101/192.168.0.141&admin&1002&{"id":1,"endTime":"20130422 180000","gapTime":"30","message":"666","startTime":"20130422 120000"}
	}
	
	/**
	 * functionName: gm1007
	 * Description: 删除公告1007
	 * Author: Kim
	 * Date:2013-5-8 15:19:02
	 **/
	public function gm1007($args, $sip, $port, $loginName){
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 1007;
		$json = json_encode($args);
		$str = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($str);
	}

	/**
	 * functionName: gm1003
	 * Description: 角色冻结 1003
	 * Author: Kim
	 * Date: 2013-4-11 16:06:02
	 **/
	public function gm1003($args,$sip,$port,$loginName){
		//$sip = "192.168.0.109";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 1003;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
		//http://192.168.0.109:20101/192.168.0.141&haha&1003&{"names":["邓小2"],"freezeTime":true}
	}
	
	/**
	 * functionName: gm1006
	 * Description: 题玩家下线 1006
	 * Author: Kim
	 * Date: 2013-4-11 16:06:02
	 **/
	public function gm1006($args,$sip,$port,$loginName){  //踢玩家下线
		//$sip = "192.168.0.109";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 1006;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
		//http://192.168.0.14:20101/192.168.0.141&haha&1006&{"names":["123","456","789"]}
	}
	
	/**
	 * functionName: gm3001
	 * Description: 单人发邮件发送 3001
	 * Author: Kim
	 * Date: 2013-5-2 9:52:27
	 **/
	public function gm3001($args,$sip,$port,$loginName){
		//$sip = "192.168.0.107";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,1000000);
		$code= 3001;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		
		return $this->autohttp($url);
	}
	
	/**
	 * functionName: gm3002
	 * Description: 全服邮件发送 3002
	 * Author: Kim
	 * Date: 2013-5-2 9:52:31
	 **/
	public function gm3002($args,$sip,$port,$loginName){
		//$sip = "192.168.0.107";
		$ip = '127.0.0.1';
		$name = $loginName.rand(1,1000000);
		$code= 3002;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);

		return $this->autohttp($url);
	}
	
	
	
	/**
	 * functionName: gm3003
	 * Description: 获取全部群邮件
	 * Author: xiaochengcheng
	 * Date: 2013-7-11 10:44:47
	 **/
	public function gm3003($args,$sip,$port,$loginName){
		//$sip = "192.168.0.107";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 3003;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
	}
	
	/**
	 * functionName: gm3004
	 * Description: 删除群邮件
	 * Author: xiaochengcheng
	 * Date: 2013-7-11 10:44:47
	 **/
	public function gm3004($args,$sip,$port,$loginName){
		//$sip = "192.168.0.107";
		$ip = $this->getUserIp();
		$name = $loginName.rand(1,100);
		$code= 3004;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
	}

	/**
	 * functionName: gm4001
	 * Description: 单人充值发送 4001
	 * Author: xiaoliao
	 * Date: 2013-12-09 9:52:27
	 **/
	public function gm4001($username,$money,$tid){
		
		$a = $username;
		$player_id =$player_id1 = $account_code= $account_code1 = $player_id2 =$account_code2 =0;
      	$link=mysql_connect("10.10.10.228:3309","wm_49","wm_49_2013_8Hs3yTHGH");		  
				  if (!$link) {
            die(' 连接失败 ' . mysql_error());
          }
        mysql_select_db("troh_game", $link); //选择数据库
        $q = "SELECT player_id,account_code FROM t_player where name ='{$a}'"; //SQL查询语句

        mysql_query("SET NAMES utf8");
        $rs = mysql_query($q, $link); //获取数据集
        if(!$rs){die("没有任何数据可显示1");}
        $row = array();
        
        while($row = mysql_fetch_row($rs)){
        		$player_id = $row[0];
        		$account_code = $row[1];
        }
        
        mysql_close($link);
        $link2=mysql_connect("localhost","phpuser","yfphpweb2013@)!#");

        mysql_select_db("game_info", $link2); //选择数据库
        $q1 = "SELECT s_biaoshi FROM servers where s_id ={$tid}"; //SQL查询语句
        mysql_query("SET NAMES utf8");
         $qs = mysql_query($q1, $link2); //获取数据集
         if(!$qs){die("没有任何数据可显示2");}
        $row1 = array();
        $sid = '';
        while($row1 = mysql_fetch_row($qs)){
        		$sid = $row1[0];
        }
        mysql_close($link);

        $link3=mysql_connect("10.10.10.228:3310","wm_phpuser","wm_49_php_user_2013_8Hs3yTHGH");
        mysql_select_db("login_game", $link3); //选择数据库
        $q2 = "SELECT name FROM account_data where id ={$account_code}"; //SQL查询语句
        $qs1 = mysql_query($q2, $link3); //获取数据集
         if(!$qs1){die("没有任何数据可显示3");}
        $row2 = array();
        $acc_id = '';
        while($row2 = mysql_fetch_row($qs1)){
        		$acc_id = $row2[0];
        }

        $link4=mysql_connect("119.145.254.9:3322","jianhh","wm_jianhh_2013_8HyHgH");
        mysql_select_db("troh_game", $link4); //选择数据库
        $q3 = "SELECT player_id,account_code FROM t_player where name ='{$a}'"; //SQL查询语句
        $qs10 = mysql_query($q3, $link4); //获取数据集
         if(!$qs1){die("没有任何数据可显示4");}
        $row3 = array();
        
        while($row3 = mysql_fetch_row($qs10)){
        		$player_id1 = $row3[0];
        		$account_code1 = $row3[1];
        }

      	$link5=mysql_connect("183.60.41.228:3308","wm_phpuser","wm_49_php_user_2013_8Hs3yTHGH");		  
		if (!$link5) {
            die(' 连接失败 1' . mysql_error());
          }
        mysql_select_db("troh_game", $link5); //选择数据库
        $q5 = "SELECT player_id,account_code FROM t_player where name ='{$a}'"; //SQL查询语句

        mysql_query("SET NAMES utf8");
        $rs1 = mysql_query($q5, $link5); //获取数据集
        if(!$rs1){die("没有任何数据可显示1");}
        $row5 = array();
        
        while($row5 = mysql_fetch_row($rs1)){
        		$player_id2 = $row5[0];
        		$account_code2 = $row5[1];
        }

		//$sip = "192.168.0.107";
		//error_reporting(0);
         $money = $money/10;
		$server_id 	= $sid	;	//服数
		//$uid 		= $_POST['uid'];			//玩家角色ID
		if ($server_id == 'S3') {
			$uid 		= $player_id;			//玩家角色ID playid
			$account 	= $acc_id;//玩家账号
		}elseif($server_id == 'S2'){
			$uid 		= $player_id2;			//玩家角色ID playid
			$account 	=$account_code2;
		}else{
			$uid 		= $player_id1;			//玩家角色ID playid
			$account 	=$account_code1;
		}
				
		//$account 	= '10086';		//玩家账号
		$time 		= time();		//时间戳
		$script_key = '@#$%^&*())(*&XCFG5145';
		$pt 		= '49you';			//平台
		$order		= 'dzs_'.date('YmdHis').rand(100000,2222222);		//订单号
		//$money		= $_POST['menoy'];		//RMB数
		//$money		= '1000';		//RMB数
		$type		= urlencode('测试');		//充值渠道 中文
		$acctime	= time();		//充值时间（时间戳）
		$sign 		= md5('service='.$server_id.'&uid='.$uid.'&money='.$money.'&order='.$order.'&acctime='.$acctime.'&time='.$time.$script_key);		//加密
		
		if ($server_id == 'S3') {
			$url = "http://csjk2.aofyx.com/chongzhi/pay.php?service=$server_id&uid={$uid}&account={$account}&time=$time&sign=$sign&pt=$pt&order=$order&type=$type&acctime=$acctime&money=$money";
		}else{
			$url = "http://csjk.aofyx.com/chongzhi/pay.php?service=$server_id&uid={$uid}&account={$account}&time=$time&sign=$sign&pt=$pt&order=$order&type=$type&acctime=$acctime&money=$money";
		}
		
		 $json = file_get_contents($url);
		if($json != 1){
		 echo "充值失败".$json;
		}//else{
		// 	echo "<font color=red>"."充值错误."."</font>";
		// }
	}
	
	/**
	 * functionName: getUserIp
	 * Description: 获取登录用户IP
	 * Author: Kim
	 * Date: 2013-4-11 16:06:02
	 **/
	private function getUserIp(){
		$ip = "127.0.0.1";
		return $ip;
	}
	
	public function myUrlEncode($string) {
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		return str_replace($entities, $replacements, urlencode($string));
	}

}


?>