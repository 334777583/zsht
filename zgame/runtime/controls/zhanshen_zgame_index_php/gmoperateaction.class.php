<?php
/**
 * FileName: gmoperate.class.php
 * Description:用户管理工具-批量冻结(解冻)账号
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 下午5:06:58
 * Version:1.00
 */
class gmoperateAction extends Common {
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
			if(!in_array("00500100", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 批量冻结(解冻)账号首页
	 */
	public function show(){
		$ipList = parent::getGmList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_operate");
	}
}