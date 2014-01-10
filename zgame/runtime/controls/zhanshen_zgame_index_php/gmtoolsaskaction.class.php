<?php
/**
 * FileName: gmtoolsask.class.php
 * Description:用户管理工具(GM)-道具申请
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */
class gmtoolsaskAction extends Common {
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00500400", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getGmList();
		$this->assign("code", $this->user["code"]);
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_tools_ask");
	}
}