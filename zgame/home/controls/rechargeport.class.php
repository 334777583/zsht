<?php
/**
 * FileName: rechargeport.class.php
 * Description:充值接口
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-11 15:18:59
 * Version:1.00
 */
class rechargeport{
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
			if(!in_array("00100600", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$this->display("recharge/recharge_port");
	}
}