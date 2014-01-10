<?php
/**
 * FileName: singSevact.class.php
 * Description:单服活跃
 * Author: xiaoliao
 * Date:2013-11-27 09:10:34
 * Version:1.00
 */
class singsevactAction extends Common {
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
			if(!in_array("00300400", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		
		$this->assign('startDate', date('Y-m-d',strtotime('-7 day')));	//开始时间，默认当天的前七天
		$this->assign('endDate', date("Y-m-d",strtotime('-1 day')));	//结束时间，默认今天
			$this->display("stickiness/singsevact");
		
	}
}