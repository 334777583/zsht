<?php
/**
 * FileName: gmoperate.class.php
 * Description:用户管理工具-批量冻结(解冻)账号
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 下午5:06:58
 * Version:1.00
 */
class gmoperate{
	/**
	 * 角色名（多个以逗号分隔）
	 * @var string
	 */
	public $rolename;
	
	/**
	 * 操作原因
	 * @var string
	 */
	public $reason;
	
	/**
	 * 禁言时长
	 * @var int
	 */
	public $stoptime;
	
	/**
	 * 冻结时长
	 * @var int
	 */
	public $freezetime;
	
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
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
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00500100", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->gm = new autogm();
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->stoptime = get_var_value("stoptime") == NULL?-1:get_var_value("stoptime");
		$this->freezetime = get_var_value("freezetime") == NULL?-1:get_var_value("freezetime");
		$this->reason = get_var_value("reason") == NULL?"":get_var_value("reason");
		$this->rolename = get_var_value("rolename") == NULL?"":get_var_value("rolename");
		$this->pageSize = get_var_value("pageSize") == NULL?10:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
	}
	
	
	/**
	 * 玩家禁言
	 */
	public function stoptalk(){
		$obj = D("game_info");
		$ip = "";				//服务器IP
		$loginName = "";		//当前登录用户
		$port = 0;				//端口
		//list($sip) = autoConfig::getServer($ip);
		$server = $obj->table("servers")->where(array("s_id"=>$this->ip,"s_flag" => 1))->find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
		$info = array();
		$ids = array();			//保存当前操作数据库返回的记录id
		if(empty($this->rolename)){
			echo "{'error':'请输入角色名！'}";
			exit;
		}
		$rolename = explode(";",$this->rolename);
		$info["names"] = $rolename;
		$info["time"] = $this->stoptime;

		$callReasult = $this->gm->gm1001($info,$ip,$port,$loginName);	//调用gm接口
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm1001($info,$ip,$port,$loginName);
			if($callReasult == "error") {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$arr = explode("|",$callReasult);
		$result = json_decode($arr[1],true);
		switch($result["result"]){
			case 0 :$result["result"] = 1;break;				//请求成功
			case 1 :$result["result"] = 2;break;				//请求失败
		}
		$openTime = date("Y-m-d H:i:s");
		if($this->stoptime > 0){                      			//计算解禁时间
			$openTime = date("Y-m-d H:i:s",time() + $this->stoptime);
		}
		foreach($rolename as $name){
			switch($result["resultMap"][$name]){
				case 0 :$result["resultMap"][$name] = 1;break;	//角色禁言成功
				case 1 :$result["resultMap"][$name] = 2;break;	//角色禁言失败
				case 2 :$result["resultMap"][$name] = 3;break;	//角色不在线
			}
			$id = $obj->table("stop_speak")->insert(array(
					"s_ip"=>$this->ip,
					"s_role_id"=>"1",
					"s_role_name"=>$name,
					"s_status"=> "1",
					"s_time" => $openTime,
					"s_secends"=>$this->stoptime,
					"s_reason" => $this->reason,
					"s_operaor" =>$this->user["username"],
					"s_callstatus" => $result["result"],
					"s_rolestatus" => $result["resultMap"][$name],
					"s_inserttime" => date("Y-m-d H:i:s")
			
			)); 
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(",",$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 获取禁言操作数据库信息
	 */
	public function getStopInfo(){
		$total = 0;							//记录总数
		$obj = D("game_info");
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$total = $obj -> table("stop_speak") -> where("s_ip = ".$this->ip) -> order("s_inserttime desc") -> total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax","go","page");
		$pageHtml = $page->getPageHtml();
		$list = $obj->table("stop_speak")->where("s_ip = ".$this->ip)->order("s_inserttime desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$result = array(
				"list"=>$list,
				"pageHtml"=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	} 
	
	
	/**
	 * 玩家解禁
	 */
	public function allowtalk(){
		$obj = D("game_info");
		$ip = "";//服务器IP
		$loginName = "";//当前登录用户
		$port = 0;//端口
		$server = $obj -> table("servers") -> where(array("s_id"=>$this->ip)) -> find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
		$info = array();
		$ids = array();
		if(empty($this->rolename)){
			echo "{'error':'请输入角色名！'}";
			exit;
		}
		$rolename = explode( ";",$this->rolename);
		$info["names"] = $rolename;
		$info["time"] = 0;

		$callReasult = $this->gm->gm1001($info,$ip,$port,$loginName);//调用gm接口
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm1001($info,$ip,$port,$loginName);
			if($callReasult == "error"){
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$arr = explode("|",$callReasult);
		$result = json_decode($arr[1],true);
		switch($result["result"]){
			case 0 :$result["result"] = 1;break;//请求成功
			case 1 :$result["result"] = 2;break;//请求失败
		}
		foreach($rolename as $name){
			switch($result["resultMap"][$name]){
				case 0 :$result["resultMap"][$name] = 1;break;//角色解禁成功
				case 1 :$result["resultMap"][$name] = 2;break;//角色解禁失败
				case 2 :$result["resultMap"][$name] = 3;break;//角色不在线
			}
			$id = $obj->table("stop_speak")->insert(array(
					"s_ip"=>$this->ip,
					"s_role_id"=>"1",
					"s_role_name"=>$name,
					"s_status"=> "2",
					"s_time" => date("Y-m-d H:i:s"),
					"s_secends"=>0,
					"s_reason" => $this->reason,
					"s_operaor" =>$this->user["username"],
					"s_callstatus" => $result["result"],
					"s_rolestatus" => $result["resultMap"][$name],
					"s_inserttime" => date("Y-m-d H:i:m")
			
			)); 
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(",",$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 玩家冻结
	 */
	public function freeze(){
		$obj = D("game_info");
		$ip = "";//服务器IP
		$loginName = "";//当前登录用户
		$port = 0;//端口
		$server = $obj->table("servers")->where(array("s_id"=>$this->ip))->find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
		$info = array();
		$ids = array();//
		if(empty($this->rolename)){
			echo "{'error':'请输入角色名！'}";
			exit;
		}
		$rolename = explode( ";",$this->rolename);
		$info["names"] = $rolename;
		$info["freezeTime"] = $this->freezetime;

		$callReasult = $this->gm->gm1003($info,$ip,$port,$loginName);	//调用gm接口
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm1003($info,$ip,$port,$loginName);
			if($callReasult == "error") {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$arr = explode("|",$callReasult);
		$result = json_decode($arr[1],true);
		switch($result["result"]){
			case 0 :$result["result"] = 1;break;//请求成功
			case 1 :$result["result"] = 2;break;//请求失败
		}
		$openTime = date("Y-m-d H:i:s");
		if($this->freezetime > 0){                      //计算解冻时间
			$openTime = date("Y-m-d H:i:s",time() + $this->freezetime);
		}
		foreach($rolename as $name){
			switch($result["resultMap"][$name]){
				case 0 :$result["resultMap"][$name] = 1;break;//角色冻结成功
				case 1 :$result["resultMap"][$name] = 2;break;//角色冻结失败
				case 2 :$result["resultMap"][$name] = 3;break;//角色不在线
			}
			$id = $obj->table("freeze")->insert(array(
					"f_ip"=>$this->ip,
					"f_role_id"=>"1",
					"f_role_name"=>$name,
					"f_status"=> "1",
					"f_time" => $openTime,
					"f_secends"=>$this->freezetime,
					"f_reason" => $this->reason,
					"f_operaor" =>$this->user["username"],
					"f_callstatus" => $result["result"],
					"f_rolestatus" => $result["resultMap"][$name],
					"f_inserttime" => date("Y-m-d H:i:m")
									
			));
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(",",$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 玩家解冻
	 */
	public function unfreeze(){
		$obj = D("game_info");
		$ip = "";		//服务器IP
		$loginName = "";//当前登录用户
		$port = 0;		//端口
		$server = $obj->table("servers")->where(array("s_id"=>$this->ip))->find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
		$info = array();
		$ids = array();//
		if(empty($this->rolename)){
			echo "{'error':'请输入角色名！'}";
			exit;
		}
		$rolename = explode( ";",$this->rolename);
		$info["names"] = $rolename;
		$info["freezeTime"] = 0;

		$callReasult = $this->gm->gm1003($info,$ip,$port,$loginName);//调用gm接口
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm1003($info,$ip,$port,$loginName);
			if($callReasult == "error") {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}

		$arr = explode("|",$callReasult);
		$result = json_decode($arr[1],true);
		switch($result["result"]){
			case 0 :$result["result"] = 1;break;			//请求成功
			case 1 :$result["result"] = 2;break;			//请求失败
		}
		foreach($rolename as $name){
			switch($result["resultMap"][$name]){
				case 0 :$result["resultMap"][$name] = 1;break;//角色解冻成功
				case 1 :$result["resultMap"][$name] = 2;break;//角色解冻失败
				case 2 :$result["resultMap"][$name] = 3;break;//角色不在线
			}
			$id = $obj->table("freeze")->insert(array(
					"f_ip"=>$this->ip,
					"f_role_id"=>"1",
					"f_role_name"=>$name,
					"f_status"=> "2",
					"f_time" => date("Y-m-d H:i:s"),
					"f_secends"=>0,
					"f_reason" => $this->reason,
					"f_operaor" =>$this->user["username"],
					"f_callstatus" => $result["result"],
					"f_rolestatus" => $result["resultMap"][$name],
					"f_inserttime" => date("Y-m-d H:i:m")
							
			));
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(",",$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 获取冻结操作数据库信息
	 */
	public function getFreezeInfo(){
		$total = 0;//记录总数
		$obj = D("game_info");
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$total = $obj->table("freeze")-> where("f_ip = ".$this->ip) -> order("f_inserttime desc")->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"freezeAjax","fgo","fpage");
		$pageHtml = $page->getPageHtml();
		$list = $obj->table("freeze")-> where("f_ip = ".$this->ip) ->order("f_inserttime desc,f_id desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$result = array(
				"list"=>$list,
				"pageHtml"=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 强制下线
	 */
	public function offline(){
		$obj = D("game_info");
		$ip = "";			//服务器IP
		$loginName = "";	//当前登录用户
		$port = 0;			//端口
		$server = $obj->table("servers")->where(array("s_id"=>$this->ip))->find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
		$info = array();
		$ids = array();
		if(empty($this->rolename)){
			echo "{'error':'请输入角色名！'}";
			exit;
		}
		$rolename = explode( ";",$this->rolename);
		$info["names"] = $rolename;

		$callReasult = $this->gm->gm1006($info,$ip,$port,$loginName);//调用gm接口
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm1006($info,$ip,$port,$loginName);
			if($callReasult == "error") {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$arr = explode("|",$callReasult);
		$result = json_decode($arr[1],true);
		switch($result["result"]){
			case 0 :$result["result"] = 1;break;//请求成功
			case 1 :$result["result"] = 2;break;//请求失败
		}
		foreach($rolename as $name){
			switch($result["resultMap"][$name]){
				case 0 :$result["resultMap"][$name] = 1;break;//角色下线成功
				case 1 :$result["resultMap"][$name] = 2;break;//角色下线失败
				case 2 :$result["resultMap"][$name] = 3;break;//角色不在线
			}
			$id = $obj->table("offline")->insert(array(
					"f_ip"=>$this->ip,
					"f_role_id"=>"1",
					"f_role_name"=>$name,
					"f_status"=> "1",
					"f_time" => date("Y-m-d H:i:s"),
					"f_reason" => $this->reason,
					"f_operaor" =>$this->user["username"],
					"f_callstatus" => $result["result"],
					"f_rolestatus" => $result["resultMap"][$name],
					"f_inserttime" => date("Y-m-d H:i:m")
								
			));
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(",",$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 获取下线操作数据库信息
	 */
	public function getOfflineInfo(){
		$total = 0;							//记录总数
		$obj = D("game_info");
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$total = $obj->table("offline")-> where("f_ip = ".$this->ip)-> order("f_inserttime desc")->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"offAjax","ogo","opage");
		$pageHtml = $page->getPageHtml();
		$list = $obj->table("offline")-> where("f_ip = ".$this->ip)->order("f_inserttime desc,f_id desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$result = array(
				"list"=>$list,
				"pageHtml"=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
}