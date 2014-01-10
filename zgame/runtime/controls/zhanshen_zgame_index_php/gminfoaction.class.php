<?php
/**
 * FileName: gminfo.class.php
 * Description:用户信息查询页面
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 上午11:36:42
 * Version:1.00
 */
class gminfoAction extends Common {
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
			if(!in_array("00300100", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 用户信息查询页面
	 */
	public function show(){
		$ipList = parent::getGmList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_info");
	}
}