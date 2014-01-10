<?php
/**
 * FileName: rechargefengbu.class.php
 * Description:充值分布
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-5 11:52:01
 * Version:1.00
 */
class rechargefengbu{
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
			if(!in_array('00100500', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取充值分布
	 */
	public function getRecords(){
		$result = array();				
		$ip = get_var_value('ip');	
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		
		if($ip) {
			list($ip, $port, $loginName) = autoConfig::getConfig($ip);		//获取服务器信息
			$point = D("game".$ip);	
			$pay_list = $point -> table('pay_detail') -> where("left(p_creatdate,10) >= '" . $startDate ."' and left(p_creatdate,10) <= '". $endDate ."' and p_result = 0") -> order('p_creatdate asc') -> select();
				
			if($pay_list != '') {
				foreach($pay_list as $pay) {
					
				}
			}
		}
		
		echo json_encode(array(
				'result' => $result
			));
	}
}