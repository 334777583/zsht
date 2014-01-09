<?php
/**
 * FileName: rechargeport.class.php
 * Description:充值接口
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-5 11:52:01
 * Version:1.00
 */
class rechargeport{
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
			if(!in_array('00100600', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
}