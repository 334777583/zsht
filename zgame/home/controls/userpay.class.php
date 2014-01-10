<?php
/**
 * FileName: userpay.class.php
 * Description:用户付费分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-6 15:58:33
 * Version:1.00
 */
class userpay{
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
			if(!in_array("00400600", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('endDate', date("Y-m-d"));	//结束时间，默认今天
		$this->display("stickiness/user_pay");
	}
}