<?php
/**
 * FileName: shopanalysis.class.php
 * Description:商城消费分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-24 9:59:28
 * Version:1.00
 */
class shopanalysisAction extends Common {
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
			if(!in_array("00200200", $this->user["code"])){
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
		$pageId = get_var_value("pageId");
		if($pageId == '1' || $pageId == null) {
			$this->display("money/shop_analysis");
		}else if($pageId == '2') {
			$this->display("money/shop_analysis_charts");
		}
	}
}