<?php
/**
 * FileName: moneysurvey.class.php
 * Description:货币收支概况
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-6 15:58:33
 * Version:1.00
 */
class moneysurvey{
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
			if(!in_array("00200300", $this->user["code"])){
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
		$this->display("money/money_survey");
	}
}