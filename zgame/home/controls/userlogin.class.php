<?php
/**
 * FileName: userlogin.class.php
 * Description: 登录概况
 * Author: xiaochengcheng
 * Date: 2013-3-11 15:51:01
 * Version: 1.00
 **/
class userlogin{
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
			if(!in_array("00400200", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
		
	/**
	 * 显示用户登录概况
	 */
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('furl',FURL);
		$this->display("stickiness/user_login");
	}
	
}
?>