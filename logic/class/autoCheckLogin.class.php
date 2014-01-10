<?php
/**
 * FileName: CheckLogin.class.php
 * Description: 验证用户登录
 * Author: xiaochengcheng
 * Date: 2013-4-8 14:28:42
 * Version: 1.00
 **/
class autoCheckLogin{
	/**
	 * 检查是否登录
	 */
	public static function  isLogin(){
		if(isset($_SESSION["user2"])){
 			$user = unserialize($_SESSION["user2"]);
		}
 		if(isset($user)){
 			return $user;
 		}else{
 			return null;
 		}
	}
}