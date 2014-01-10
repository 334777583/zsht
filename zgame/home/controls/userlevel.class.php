<?php
/**
 * FileName: userlevel.class.php
 * Description:新增用户等级分布
 * Author: hjt
 * Date: 2013-8-28 14:00:34
 * Version:1.00
 */
class userlevel{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00401300", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign("endday",date('Y-m-d H:i:s'));
		$this->display("stickiness/user_level");
	}
}