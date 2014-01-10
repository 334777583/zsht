<?php
class SysuserModel extends Dmysqli {
	/**
	 * 检查是否登录
	 */
	public function  isLogin(){
		if(isset($_SESSION["user2"]) && $_SESSION["user2"] != ''){
			return  unserialize($_SESSION["user2"]);
		}
		$uri=B_APP.'/'."index/index";
		echo '<script>';
		echo 'if(window.parent){window.parent.location="'.$uri.'";}';
		echo 'window.location="'.$uri.'";';
		echo '</script>';
		exit();
	}
}
