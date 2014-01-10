<?php
/**
 * FileName: singsevrak.class.php
 * Description:单服排行
 * Author: hjt
 * Date:2013-8-29 16:10:34
 * Version:1.00
 */
class singsevrak{
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
			if(!in_array("00300300", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		
		
			$this->display("stickiness/singsevrak");
		
	}
}