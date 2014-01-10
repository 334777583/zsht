<?php
/**
 * FileName: platformquery.class.php
 * Description:平台记录查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-6-18 10:06:01
 * Version:1.00
 */
class platformquery{
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
			if(!in_array("00501500", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->display("system/platform_query");
	}
}