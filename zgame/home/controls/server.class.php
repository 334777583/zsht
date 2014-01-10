<?php
class server{
	/**
	 * 主键id
	 * @var int
	 */
	private $id;
	
	/**
	 * 名字
	 * @var string
	 */
	private $name;
	
	/**
	 * 服务器ip
	 * @var char
	 */
	private $ip;
	
	/**
	 * 服务器ip
	 * @var char
	 */
	private $domain;
	
	/**
	 * 端口
	 * @var int
	 */
	private $port;
	
	/**
	 * 用户信息
	 * @var array
	 */
	private $user;
	
	
	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00501100", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
		$this->name = get_var_value("name") == NULL?"":get_var_value("name");
		$this->ip =  get_var_value("ip") == NULL?"":get_var_value("ip");
		$this->id = get_var_value("id") == NULL?0:get_var_value("id");
		$this->sid = get_var_value("sid") == NULL?0:get_var_value("sid");
		$this->port = get_var_value("port") == NULL?"":get_var_value("port");
		$this->domain = get_var_value("domain") == NULL?null:get_var_value("domain");
	}
	
	
	
	/**
	 * 显示服务器管理页面
	 */
	public function show(){
		$servers = D("servers");
		$plist = $servers->where(array("s_flag"=>1))->select();
		$this->assign("plist",$plist);
		$this->display("system/gm_db");
	}
	
	/**
	 * 根据id获取服务器信息
	 */
	public function getById(){
		$server = D("servers");
		$bo = $server->where(array("s_id"=>$this->id))->find();
		echo json_encode($bo);
		exit;		
	}
	
	/**
	 * 保存服务器数据
	 */
	public function save(){
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$servers = D("servers");
		$state = $servers->where(array("s_id"=>$this->id))->update(array(
				"s_sid"=>$this->sid,
				"s_name"=>$this->name,
				"s_domain" => $this->domain,
				"s_ip"=>$this->ip,
				"s_port"=>$this->port,
				"s_inserttime" => date("Y-m-d H:i:s")
		));
		if($state ==  false){
			echo json_encode("error");
			exit;
		}
		echo json_encode("success");
	}
	
	/**
	 * 删除
	 */
	public  function delete(){
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$servers = D("servers");
		$state = $servers->where(array("s_id"=>$this->id))->update(array(
				"s_flag"=> 2
		));
		if($state != false){
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	/**
	 * 添加
	 */
	public function add(){
		$servers = D("servers");
		$state = $servers->insert(array(
				"s_name"=>$this->name,
				"s_ip"=>$this->ip,
				"s_port"=>$this->port,
				"s_domain"=>$this->domain,
				"s_inserttime" => date("Y-m-d H:i:s")
		
		));
		if(!$state ==  false){
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
}