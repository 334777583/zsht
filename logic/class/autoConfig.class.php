<?php
/**
 * FileName: autoConfig.class.php
 * Description: 获取服务器信息
 * Author: xiaochengcheng
 * Date: 2013-5-4 13:49:29
 * Version: 1.00
 **/
class autoConfig{
	/**
	 * 根据id获取gm服务器配置信息
	 */
	public static function getConfig($sip){
		$obj = D("game_base");
		$ip = "";			//服务器IP
		$gid = 0;			//开服ID
		$sid = 0;			//c++游戏服务器ID
		$server = $obj->table("servers")->where(array("s_id"=>$sip,"s_flag" => 1))->find();
		
		if($server != '') {
			if(!empty($server['s_domain'])) {			//优先域名
				$ip = $server['s_domain'];
			} else {
				$ip = $server["s_ip"];
			}
			
			$gid = $server['s_gid'];
			$sid = $server['s_sid'];
			
		}

		return array($ip, $gid, $sid);
	}
	
	/**
	 * 获取全部gm服务器配置信息
	 */
	public static function getIPS(){
		$obj = D("game_base");
		$result = array();
		$ipList = $obj->table("servers")->where(array("s_flag" => 1))->select();	//获取服务器信息
		if(is_array($ipList)) {
			foreach($ipList as $item) {
				$result[$item['s_id']] = $item['s_name'];
			}
		}
		
		return $result;
	}
	
	
	/**
	 * 根据ID获取开服信息
	 */
	public static function getNameByIp($sip) {
		$obj = D("game_base");
		$name = "";
		$game = $obj->table("gamedb")->where(array("g_id"=>$sip,"g_flag" => 1))->find();
		if(isset($game['g_name'])) {
			$name = $game['g_name'];
		}
		
		return array($name);
	}
	
	
	
}