<?php
/**
 * FileName: platformquery.class.php
 * Description:平台记录查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-6-18 10:06:01
 * Version:1.00
 */
class platformquery{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00501500', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取平台记录
	 */
	public function getRecords(){
		$ip = get_var_value('ip');
		$des = get_var_value('des');
		$flag = get_var_value('flag');
		$pageSize = get_var_value('pageSize') == null? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == null? 1 : get_var_value('curPage');
		if($ip) {
			$obj = D("game".$ip);
			
			if($des) {
				$list = $obj -> table('ptlogin') -> where('p_decript = "'.$des.'"') -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) -> select();
				$total = $obj -> table('ptlogin') -> where('p_decript = "'.$des.'"') -> total();
			}else {
				$list = $obj -> table('ptlogin') -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) -> select();
				$total = $obj -> table('ptlogin') -> total();
			}
			
			$option = '';	//下拉框内容
			if($flag) {
				$option = $obj -> table('ptlogin') -> field('distinct p_decript') -> select();
			}
			
			$page = new autoAjaxPage($pageSize,$curPage,$total,"formAjax","go","page");
			$pageHtml = $page->getPageHtml();
			
			echo json_encode(array(
					'result' => $list,
					'option' => $option,
					'pageHtml'=> $pageHtml
				));
		}else {
			echo '1';
		}
	}
}