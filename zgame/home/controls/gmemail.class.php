<?php
/**
 * FileName: gmemail.class.php
 * Description:用户管理工具-邮件
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午3:40:29
 * Version:1.00
 */
class gmemail{
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
			if(!in_array("00500300", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
/* 		$o = new IpSearchDat();
		echo $o->findIp('218.19.227.180');
 */		$ipList = parent::getGmList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_email");
	}
	
	public function showdel() {
		$ipList = parent::getGmList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_email_del");
	}
}