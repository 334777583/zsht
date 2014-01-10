<?php
/**
 * FileName: usernew.class.php
 * Description:新增用户留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-6-26 16:10:34
 * Version:1.00
 */
class usernewAction extends Common {
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
			if(!in_array("00400800", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('startDate', date("Y-m-d",strtotime('-1 day')));
		
		$pageId = get_var_value("pageId");
		if($pageId == '1' || $pageId == null) {
			$this->display("stickiness/user_new");
		}else if($pageId == '2') {
			$this->display("stickiness/user_lose");
		}else if($pageId == '3') {
			$this->display("stickiness/user_task");
		}
	}
}