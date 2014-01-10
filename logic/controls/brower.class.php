<?php
/**
 * FileName: brower.class.php
 * Description:浏览器记录查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-6-24 11:49:53
 * Version:1.00
 */
class brower{
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
			if(!in_array('00501600', $this->user['code'])){
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
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		
		if(!$startDate) {
			$startDate = date('Y-m-d',strtotime('-7 day'));	
		}
		
		if(!$endDate) {
			$endDate =  date("Y-m-d",strtotime('-1 day'));
		}
		
		if($ip) {
			$obj = D("game".$ip);
			
			$list = $obj -> table('brower') ->where('b_date >= "'.$startDate.'" and b_date <="'.$endDate.'"') -> select();
			
			echo json_encode(array(
					'result' => $list
				));
		}else {
			echo '1';
		}
	}
}