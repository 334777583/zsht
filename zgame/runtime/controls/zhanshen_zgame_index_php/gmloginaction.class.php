<?php
/**
 * FileName: gmlogin.class.php
 * Description:用户创角分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-6 15:58:33
 * Version:1.00
 */
class gmloginAction extends Common {
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
			if(!in_array("00501000", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		//$ipDetail = parent::getDeatil();
		$this->assign("ipList",$ipList);
		//$this->assign("ipDetail",json_encode($ipDetail));	//做到这里先
		$this->assign("curl",CURL);	//做到这里先
		//$this->assign('endDate', date("Y-m-d",strtotime('-1 day')));	//结束时间，默认昨天
		$this->display("gmtools/gm_login");
	}
	
	
	
}