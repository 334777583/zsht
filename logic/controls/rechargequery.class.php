<?php
/**
 * FileName: rechargequery.class.php
 * Description:充值查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-4 14:08:50
 * Version:1.00
 */
class rechargequery{
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
			if(!in_array('00100100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取充值记录
	 */
	public function getRecords(){
		$rate = 20; 		//货币与元宝的比例
		
		$ip = get_var_value('ip');
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		$code = get_var_value('code');
		$orderKey = get_var_value('orderKey');
		$key = get_var_value('key');
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		
		if($ip) {
			$obj = D("game".$ip);
			
			$where_sql = "";
			
			if($startDate) {
				$where_sql .= "left(p_creatdate,10) >= '" . $startDate . "' and ";
			}
			if($endDate) {
				$where_sql .= "left(p_creatdate,10) <='" . $endDate . "' and ";	
			}
			if($code && $key) {
				switch($code ) {
					case 1 : $where_sql .= "p_acc = '" . $key . "' and ";break;
					case 3 : $where_sql .= "p_playid = '" . $key . "' and ";break;
				}
			}
			if($orderKey) {
				$where_sql .= "p_order = '" . $orderKey . "' and ";
			}
			
			if($where_sql !== "") {
				$where_sql = rtrim($where_sql, ' and ');
				$list = $obj -> table('pay_detail') -> where($where_sql) -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) -> order('p_creatdate desc') -> select();
				$total = $obj -> table('pay_detail') -> where($where_sql)  -> total();
			} else {
				$list = $obj -> table('pay_detail') -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) -> order('p_creatdate desc') -> select();
				$total = $obj -> table('pay_detail') -> total();
			}
			
			$page = new autoAjaxPage($pageSize, $curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			
			echo json_encode(array(
					'result' => $list,
					'rate' => $rate,
					'pageHtml' => $pageHtml
				));
		}else {
			echo '1';
		}
	}
}