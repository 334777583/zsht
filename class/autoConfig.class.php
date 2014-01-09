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
		$obj = D("game_info");
		$ip = "";			//服务器IP
		$port = 0;			//端口
		$loginName = '';	//当前登录用户
		$gid = 0;			//开服ID
		$server = $obj->table("servers")->where(array("s_id"=>$sip,"s_flag" => 1))->find();
		$gamedb = $obj->table("gamedb")->where(array("g_id"=>$sip,"g_flag"=>1))->find();
		$user = autoCheckLogin::isLogin();
		
		if (isset($server['s_domain']) && !empty($server['s_domain'])) {		//优先域名
			$ip = $server['s_domain'];
		} else if(isset($server["s_ip"])) {
			$ip = $server["s_ip"];
		}
		
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		
		if(isset($server['s_gid'])){
			$gid = $server['s_gid'];
		}
		
		
		if(isset($user['username'])){
			$loginName = $user['username'];
		}
		
		if(isset($gamedb['g_file'])){
			$gfile = $gamedb['g_file'];
		}
		
		return array($ip,$port,$loginName,$gid,$gfile);
	}
	
	/**
	 * 获取全部gm服务器配置信息
	 */
	public static function getIPS(){
		$obj = D("game_info");
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
		$obj = D("game_info");
		$name = "";
		$game = $obj->table("gamedb")->where(array("g_id"=>$sip,"g_flag" => 1))->find();
		if(isset($game['g_name'])) {
			$name = $game['g_name'];
		}
		
		return array($name);
	}

	/**
	 * 根据ID获取开服信息
	 */
	public static function getServer($sip) {
		$obj = D("game_info");
		$ip = "";			//服务器IP
		$domain = "";		//服务器域名
		$port = 0;			//端口
		$loginName = '';	//当前登录用户
		$gid = 0;			//开服ID
		$server = $obj->table("servers")->where(array("s_id"=>$sip,"s_flag" => 1))->find();
		$user = autoCheckLogin::isLogin();
		
		if (isset($server['s_ip']) && !empty($server['s_ip'])) {
			$ip = $server["s_ip"];
		}
		
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		
		if(isset($server['s_gid'])){
			$gid = $server['s_gid'];
		}
		
		if(isset($server['s_domain'])){
			$domain = $server['s_domain'];
		}
		
		if(isset($user['username'])){
			$loginName = $user['username'];
		}	
		
		return array($ip,$port,$loginName,$gid,$domain);
	}
	
	
	
}