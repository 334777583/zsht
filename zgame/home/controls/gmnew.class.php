<?php
/**
 * FileName: gmnew.class.php
 * Description:用户管理工具-公告管理
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午5:49:26
 * Version:1.00
 */
class gmnew{
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
			if(!in_array("00500200", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getGmList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_new");
	}
}