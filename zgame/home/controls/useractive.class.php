<?php
/**
 * FileName: useractive.class.php
 * Description: 用户活跃分析
 * Author: xiaochengcheng
 * Date: 2013-3-18 14:09:01
 * Version: 1.00
 **/
class useractive{
	/**
	 * 登录用户信息
	 */
	private $user;
	
	/**
	 * 页面ID（1：双周留存；2：活跃粘性）
	 * @var int
	 */
	private $pageId;
	
	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00400300", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
		$this->pageId =  get_var_value("pageId") == NULL?1:get_var_value("pageId");
	}
	
	/**
	 * 显示活跃分析页面
	 */
	public function show(){
		$ipList = parent::getIpList();
		$this->assign('date', date("Y-m-d",strtotime("-14 day")));
 		$this->assign("ipList",$ipList);
		if( '1' == $this->pageId ) {
			$this->display("stickiness/user_active");
		}else {
			$this->display("stickiness/user_stick");
		}
		
	}
	
}