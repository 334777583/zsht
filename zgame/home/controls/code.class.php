<?php
/**
 * FileName: code.class.php
 * Description:激活码
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-6 15:58:33
 * Version:1.00
 */
class code{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 页面ID（1：查询页；2：生成页）
	 * @var int
	 */
	private $pageId;

	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00500600", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
		$this->pageId =  get_var_value("pageId") == NULL?1:get_var_value("pageId");
	}
	
	public function show(){	
		$ipList = parent::getGmList();
		$this->assign("ipList",$ipList);
		if( '1' == $this->pageId ) {
			$this->assign("code", $this->user["code"]);
			$this->display("gmtools/code_create");
		}else {
			$this->display("gmtools/code_query");
		}
	}
}