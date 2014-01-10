<?php

class autogm{
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
		$name = $loginName;
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
	public function gm2001($args,$sip,$port,$loginName){	//该用户的详细信息 
		//$sip = "192.168.0.104";
		$ip = $this->getUserIp();
		$name = $loginName;
		$code= 2001;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
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
		$name = $loginName;
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
		$name = $loginName;
		$code= 2003;
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
		$name = $loginName;
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
		$name = $loginName;
		$code= 1002;
		$json = json_encode($args);
		$str = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		print_R($str);
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
		$name = $loginName;
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
		$name = $loginName;
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
		$name = $loginName;
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
		$name = $loginName;
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
		$ip = $this->getUserIp();
		$name = $loginName;
		$code= 3002;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		//$surl = "http://".$sip.":".$port."/" . $ip."&". $name."&". $code."&".$json;
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
		$name = $loginName;
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
		$name = $loginName;
		$code= 3004;
		$json = json_encode($args);
		$url = "http://".$sip.":".$port."/" . urlencode($ip)."&". urlencode($name)."&". urlencode($code)."&".urlencode($json);
		return $this->autohttp($url);
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