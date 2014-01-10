<?php
/**
 * FileName: common.class.php
 * Description:共用类
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-25 下午3:29:35
 * Version:1.00
 */
class Common extends Action {
	/**
	 * 获取游戏服务器ip列表
	 * @return array
	 */
	public function getIpList(){
		$obj = D("gamedb");
		$list =  $obj  -> field('g_id as s_id, g_name as s_name, g_ip')-> order('g_id asc')->where(array("g_flag"=>1))->select();
		return $list;
	}
	
	
	/**
	 * 获取gm工具服务器ip列表
	 * @return array
	 */
	public function getGmList(){
		$obj = D("servers");
		$list =  $obj  -> field('s_id, s_name')-> order('s_id asc')->where(array("s_flag"=>1))->select();
		return $list;
	}
	
	/**
	 * 获取道具信息
	 * @return json
	 */
	public function cache(){
		$point = D("tools_detail");
		$codeList = $point -> field('t_code , t_name') -> select();
		
		$json = json_encode($codeList);
		
		//echo '<script type="text/javascript">';
		
		//echo 'if(typeof(ZHUANGBEI) == "undefined"){';
		
		echo 'ZHUANGBEI = '.$json.'';
		
		//echo '}';
		//echo '</script>';
		exit;
	}
	
	
	/**
	 * 写文件函数 
	 * @return json
	 */
	public function writeFile($str, $mode = 'a+') {
		$log = PROJECT_PATH . 'login.log';
		$handle = @fopen($log, $mode);
		@flock($handle, 3);
		if(!$handle) {
			return false;
		}else{
			@fwrite($handle, $str);
			@fclose($handle);
			return true;
		}
	}
	
	/**
	 * 获取登录用户客户端地址 
	 * @return string
	 */
	public function get_ip(){
		if($_SERVER['REMOTE_ADDR']) return $_SERVER['REMOTE_ADDR'];
		elseif($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) return $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) return $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		elseif($HTTP_SERVER_VARS["REMOTE_ADDR"]) return $HTTP_SERVER_VARS["REMOTE_ADDR"];
		elseif(getenv("HTTP_X_FORWARDED_FOR")) return getenv("HTTP_X_FORWARDED_FOR");
		elseif(getenv("HTTP_CLIENT_IP")) return getenv("HTTP_CLIENT_IP");
		elseif(getenv("REMOTE_ADDR")) return getenv("REMOTE_ADDR");
		else return '127.0.0.1';
	}
}