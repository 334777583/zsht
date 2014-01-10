<?php
/**
 * FileName: propanalysis.class.php
 * Description:道具消耗页面
 * Author: jan
 * Date:2013-11-6 上午11:36:42
 * Version:1.00
 */
class propanalysisAction extends Common {
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
			if(!in_array("00401400", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 道具消耗页面
	 */
	public function show(){
		$ipList = parent::getGmList();
		foreach($name as $key => $value){
			$type[$key]['name'] = $value; 
			$type[$key]['state'] =$state[$key];
		}
		$this->assign("startdate",date("Y-m-d",strtotime("-7 days")));
		$this->assign("enddate",date("Y-m-d"));
		$this->assign("Sname",$type);
		$this->assign("code", $this->user["code"]);
		$this->assign("ipList",$ipList);
		$this->display("money/prop_analysis");
		
	}
}