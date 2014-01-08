<?php
/**
 * FileName: gminfo.class.php
 * Description:用户信息查询页面
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 上午11:36:42
 * Version:1.00
 */
class gminfo{
	/**
	 * 每页显示记录数
	 * @var int
	 */
	private $pageSize = 10;
	
	/**
	 * 当前页
	 * @var int
	 */
	private $curPage = 1;
	
	/**
	 * 服务器IP
	 * @var string
	 */
	private $ip;
	
	/**
	 * 检索模式（0：账号；1：昵称；2：ID）
	 * @var int
	 */
	private $type;
	
	/**
	 * 查询内容
	 * @var string;
	 */
	private $text;
	
	/**
	 * 是否模糊查询（0：是；1：否）
	 * @var int
	 */
	private $fuzzy;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户信息
	 */
	public $user;
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00300100", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->pageSize = get_var_value("pageSize") == NULL?10:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->type =  get_var_value("type") == NULL?0:get_var_value("type");
		$this->text =  get_var_value("text") == NULL?"":get_var_value("text");
		$this->fuzzy =  get_var_value("fuzzy") == NULL?0:get_var_value("fuzzy");
		$this->gm = new autogm();
	}
	
	/**
	 * ajax请求用户基本信息数据
	 */
	public function get(){
		list($ip, $port, $loginName, $gid) = autoConfig::getServer($this->ip);
		$gameDb = D(GNAME.$gid);
		
		if($this->fuzzy == 0){ 	//模糊查询
			$info = array();
			$info["pageNum"] = $this->curPage;
			$info["what"] = $this->text;
			$callReasult = $this->gm->gm2003($info,$ip,$port,$loginName);	//调用gm接口
			if($callReasult == "error"){
				sleep(1);
				$callReasult = $this->gm->gm2003($info,$ip,$port,$loginName);	//如果失败，重试一次
				if($callReasult == "error"){
					echo "{'error':'远程超时无响应！'}";
					exit;
				}
			}
			
			$total = 0;				//gm返回查询总记录数
			$plays = array();		//玩家基本信息
			$arr = explode("|",$callReasult);
			if($arr[1] != null){
				$info = json_decode($arr[1],true);
			}
			if(isset($info["totalPage"])){
				$total = $info["totalPage"] * $this->pageSize;
			}
			if(isset($info["players"])){
				$plays = $info["players"];
			}
			$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax","go","page");
			$pageHtml = $page->getPageHtml();
			
			
			foreach($plays as $k => $play){		//获取玩家最近在线时间，最近登录IP，总在线时长
				$lastTime = '';					//最近在线时间
				$lastIp = '';					//最近登录IP
				$sumSec = 0	;					//总在线时长
				
				$lt = $gameDb -> table('detail_login') -> where('d_userid ='.$play['id']) -> order('d_date desc') -> find();
				if($lt != '') {					
					$ss = $gameDb -> table('online_sec') -> field('sum(o_second) as sum') ->where('o_userid ='.$play['id']) -> find(); 
					
					if (isset($lt['d_date'])) {
						$lastTime = $lt['d_date'];
					}
					
					if (isset($lt['d_ip'])) {
						$lastIp = $lt['d_ip'];
						$o = new autoipsearchdat();
						$area = $o->findIp($lastIp);
						if($area) {
							$lastIp .= '(' . $area . ')';	
						}
					}
					
					if (isset($ss['sum'])) {
						$sumSec = $ss['sum'];
					}
				}
				
				$plays[$k]['lastTime'] = $lastTime;
				$plays[$k]['lastIp'] = $lastIp;
				$plays[$k]['sumSec'] = $sumSec;
				
				@$gameDb -> table('role') -> fquery("replace into role(r_roleid, r_name, r_updatetime) values('".$play['id']."','" . $play['name'] . "','" . date('Y-m-d H:i:s') ."')");	//插入到角色缓存表
			}
			
			/*if(count($plays) > 0){
				global $login;
				$temp = F($login['db'], $login['ip'], $login['user'], $login['password'], $login['port']);
				foreach($plays as $key=>$val){
					if(isset($val['accountCode'])){
						
						$temp_arr = $temp ->field('name') -> table('account_data') -> where('id="'.$val['accountCode'].'"') -> find();
						if(count($temp_arr) > 0){
							$plays[$key]['accountCode'] = $temp_arr['name'];
						}
					}
				}
			}*/
			
			$result = array(
					'pageHtml'=>$pageHtml,
					'plays'=> $plays
			);
			echo json_encode($result);
			exit;
		}else if($this->fuzzy == 1){			//精确查询（账号，角色名，ID）
			$info = array();					//发送到gm接口的数据
			$info["queryMode"] = $this->type;
			if($this->type == 0){
			
				global $login;
				$temp = F($login['db'], $login['ip'], $login['user'], $login['password'], $login['port']);
				$temp_arr = $temp ->field('name') -> table('account_data') -> where('name="'.$this->text.'"') -> find();
				// var_dump($temp_arr);
				if(count($temp_arr)>0){
					$info["what"] = $temp_arr['id'];
				}else{
					$info["what"] = $this->text;
				}
				
			}else{
				$info["what"] = $this->text;
			}
			$callReasult = $this->gm->gm2000($info,$ip,$port,$loginName);		//调用gm接口
			if($callReasult == "error") {
				sleep(1);
				$callReasult = $this->gm->gm2000($info,$ip,$port,$loginName);
				if($callReasult == "error") {
					echo "{'error':'远程超时无响应！'}";
					exit;
				}
			}
			
			$plays = array();					//玩家基本信息
			$arr = explode("|",$callReasult);
			if($this->type == 0){				//账号查询
				if($arr[1] != "null" && isset($arr[1])){
					$info = json_decode($arr[1],true);
					if(isset($info["queryCharacters"])){
						foreach($info["queryCharacters"] as $item){
							$plays[] = $item;
							foreach($plays as $k => $play){		//获取玩家最近在线时间，最近登录IP，总在线时长
								$lastTime = '';					//最近在线时间
								$lastIp = '';					//最近登录IP
								$sumSec = 0	;					//总在线时长
								
								$lt = $gameDb -> table('detail_login') ->where('d_userid ='.$play['id']) -> order('d_date desc') -> find();
								if($lt != '') {	
									$ss = $gameDb -> table('online_sec') -> field('sum(o_second) as sum') ->where('o_userid ='.$play['id']) -> find(); 
									
									if (isset($lt['d_date'])) {
										$lastTime = $lt['d_date'];
									}
									
									if (isset($lt['d_ip'])) {
										$lastIp = $lt['d_ip'];
										$o = new autoipsearchdat();
										$area = $o->findIp($lastIp);
										if($area) {
											$lastIp .= '(' . $area . ')';	
										}
									}
									
									if (isset($ss['sum'])) {
										$sumSec = $ss['sum'];
									}
								}
								
								$plays[$k]['lastTime'] = $lastTime;
								$plays[$k]['lastIp'] = $lastIp;
								$plays[$k]['sumSec'] = $sumSec;
								
								@$gameDb -> table('role') -> fquery("replace into role(r_roleid, r_name, r_updatetime) values('".$play['id']."','" . $play['name'] . "','" . date('Y-m-d H:i:s') ."')");	//插入到角色缓存表
							}
						}
					}
				}
			}else{
				if($arr[1] != "null" && isset($arr[1])){
					$info = json_decode($arr[1],true);
					$plays[] = $info;
		
					foreach($plays as $k => $play){		//获取玩家最近在线时间，最近登录IP，总在线时长
						$lastTime = '';					//最近在线时间
						$lastIp = '';					//最近登录IP
						$sumSec = 0	;					//总在线时长
						
						$lt = $gameDb -> table('detail_login') ->where('d_userid ='.$play['id']) -> order('d_date desc') -> find();
						$ss = $gameDb -> table('online_sec') -> field('sum(o_second) as sum') ->where('o_userid ='.$play['id']) -> find(); 
						
						if (isset($lt['d_date'])) {
							$lastTime = $lt['d_date'];
						}
						
						if (isset($lt['d_ip'])) {
							$lastIp = $lt['d_ip'];
						}
						
						if (isset($ss['sum'])) {
							$sumSec = $ss['sum'];
						}
						
						$plays[$k]['lastTime'] = $lastTime;
						$plays[$k]['lastIp'] = $lastIp;
						$plays[$k]['sumSec'] = $sumSec;
						
						@$gameDb -> table('role') -> fquery("replace into role(r_roleid, r_name, r_updatetime) values('".$play['id']."','" . $play['name'] . "','" . date('Y-m-d H:i:s') ."')");	//插入到角色缓存表
					}
					
				}
			}
			
			 // print_r($plays);
			
			if(count($plays) > 0){
				global $login;
				$temp = F($login['db'], $login['ip'], $login['user'], $login['password'], $login['port']);
				foreach($plays as $key=>$val){
					if(isset($val['accountCode'])){
						
						$temp_arr = $temp ->field('name') -> table('account_data') -> where('id="'.$val['accountCode'].'"') -> find();
						if(count($temp_arr) > 0){
							$plays[$key]['accountCode'] = $temp_arr['name'];
						}
					}
				}
			}
			
			$result = array(
					'plays'=> $plays
			);
			echo json_encode($result);
			exit;
		}
	}
	
	/**
	 * 获取玩家详细信息
	 */
	public function getDetailInfo(){
	
		list($ip, $port, $loginName, $gid, $domain) = autoConfig::getServer($this->ip);
		$info = array();
		$info["what"] = $this->text;
		try{
			$callReasult = $this->gm->gm2001($info,$ip,$port,$loginName,$domain);//调用gm接口
			//print_R($callReasult);
			if($callReasult == "error"){
				sleep(1);
				$callReasult = $this->gm->gm2001($info,$ip,$port,$loginName,$domain);
				if($callReasult == "error") {
					echo "{'error':'远程超时无响应！'}";
					exit;
				}
			}
		}catch(Exception $e){
			echo "{'error':'远程请求失败！'}";
			exit;
		}
		$play = array();		//玩家详细信息
		$isOnline = 0;			//在线状态 （正数表示在线，负数表示不在线）
		$accountState = -1;		//账号状态 (冻结3，永久禁言2，限时禁言1，正常0)
		$equips = array();		//玩家的装备
		$isNull = false;		//是否请求返回空
		
		
		
		$arr = explode("|",$callReasult);
		
		if(isset($arr[1]) && $arr[1] != "null"){
			$arr[1] = rtrim($arr[1] ,'\']');
			$info = json_decode($arr[1],true);
		}else{
			$isNull = true;
		}
		
		if(isset($info["player"])){
			$play = $info["player"];
		}
		if(isset($info["isOnline"])){
			$isOnline = $info["isOnline"];
		}
		if(isset($info["accountState"])){
			$accountState = $info["accountState"];
		}
		if(isset($info["equips"])){
			$equips =  $info["equips"];
		}
		$result = array(
				'player' =>  $play,
				'isOnline' => $isOnline,
				'accountState' => $accountState,
				'equips' => $equips,
				'isNull'=> $isNull
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 获取背包信息
	 */
	public function getBagInfo(){
		list($ip, $port, $loginName) = autoConfig::getServer($this->ip);
		
		$info = array();
		$info["what"] = $this->text;
		try{
			$callReasult = $this->gm->gm2002($info,$ip,$port,$loginName);	//调用gm接口
			if($callReasult == "error"){
				sleep(1);
				$callReasult = $this->gm->gm2002($info,$ip,$port,$loginName);
				if($callReasult == "error") {
					echo "{'error':'远程超时无响应！'}";
					exit;
				}
			}
		}catch(Exception $e){
			echo "{'error':'远程请求失败！'}";
			exit;
		}
		$bag = array();//背包信息
		$isNull = false;//是否请求返回空
		$arr = explode("|",$callReasult);
		if($arr[1] != "null" && isset($arr[1])){
			$info = json_decode($arr[1],true);
			$bag = $info;
		}else{
			
		}
		$result = array(
				'bag' =>  $bag,
				'isNull' => $isNull
		);
		echo json_encode($result);
		exit;
	}
}