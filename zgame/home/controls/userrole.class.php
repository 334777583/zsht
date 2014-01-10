<?php
/**
 * FileName: userrole.class.php
 * Description:用户创角分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-6 15:58:33
 * Version:1.00
 */
class userrole{
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
			if(!in_array("00400700", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	// public function show(){
		// $ipList = parent::getIpList();
		// $ipDetail = parent::getDeatil();
		// $this->assign("ipList",$ipList);
		// $this->assign("ipDetail",json_encode($ipDetail));	//做到这里先
		// $this->assign("curl",CURL);	//做到这里先
		// $this->assign('endDate', date("Y-m-d",strtotime('-1 day')));	//结束时间，默认昨天
		// $this->display("stickiness/user_role");
	// }
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('startDate', date('Y-m-d',strtotime('-7 day')));	//开始时间，默认当天的前七天
		$this->assign('endDate', date("Y-m-d",strtotime('-1 day')));	//结束时间，默认昨天
		$this->display("stickiness/user_role");
	}
	
	
	public function shishi(){
		$ipList = parent::getIpList();
		//$ipDetail = parent::getDeatil();
		$this->assign("ipList",$ipList);
		//$this->assign("ipDetail",json_encode($ipDetail));	//做到这里先
		//$this->assign("curl",CURL);	//做到这里先
		$this->assign('endDate', date("Y-m-d",strtotime('-1 day')));	//结束时间，默认昨天
		$this->display("stickiness/shishi");
	}
	
	
}