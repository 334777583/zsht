<?php
/**
 * Copyright: 石佳佰林 2012
 * FileName: mysqlsession.class.php
 * Description: mysql处理session类
 * Author: 谢观如 
 * Date: 2012-07-10 13:33:16
 * Version:2.00
 **/
 class MysqlSession {
 	private static $mysql;				//mysql连接资源
 	private static $session_limit;		//session生存时间
 	/**
	 * FunctionName: start
	 * Description: 开始使用mysql处理session,设置session处理句柄
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:33:29 
 	*/
 	public static function start(){
 		session_set_save_handler(
 			array(__CLASS__, 'open'),
 			array(__CLASS__, 'close'),
 			array(__CLASS__, 'read'),
 			array(__CLASS__, 'write'),
 			array(__CLASS__, 'destroy'),
 			array(__CLASS__, 'gc')
 		);
 		self::$mysql = new Dmysqli();
 		self::$session_limit = ini_get('session.gc_maxlifetime');
 		session_start();
 	}
 	
 	/**
	 * FunctionName: open
	 * Description: 打开session时,触发处理函数
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:34:43 
 	*/
 	public static function open(){
 		self::gc();
 		return true;
 	}
 	
 	/**
	 * FunctionName: close
	 * Description: 关闭session时,触发处理函数
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:34:43 
 	*/
 	public static function close(){
 		return true;
 	}
 	
 	/**
	 * FunctionName: write
	 * Description: 写session时,触发处理函数
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:34:43 
 	*/
 	public static function write($id, $info){
 		$time = time() + self::$session_limit;
 		$tmp = explode(';', $info);
 		$uid = 0;
 		$u_type = 0;
		foreach($tmp as $val){
			$tmp_str = explode('|',$val);
			if($tmp_str[0] == 'uid'){
				$tmp_str = explode(':', $tmp_str[1]);
				$uid = end($tmp_str);
				$uid = intval(trim($uid, '"'));
				$u_type = 1;
				break;
			}elseif($tmp_str[0] == 'admin_id'){
				$tmp_str = explode(':', $tmp_str[1]);
				$uid = end($tmp_str);
				$uid = intval(trim($uid, '"'));
				$u_type = 2;
				break;
			}
		}
 		$sql = "REPLACE INTO  session(sess_id, sess_u_id, sess_u_type, sess_u_info, sess_limit_time) VALUES('$id', '$uid', '$u_type', '$info', '$time')";
 		self::$mysql -> query($sql, 'insert');
 		return true;
 	}
 	
 	/**
	 * FunctionName: read
	 * Description: 读session时,触发处理函数
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:34:43 
 	*/
 	public static function read($id){
		$sql = "SELECT sess_u_info FROM session WHERE sess_id = '$id'";
		$result = self::$mysql -> query($sql, 'select');
		return $result == false ? array() : $result[0]['sess_u_info'];
 	}
 	
 	/**
	 * FunctionName: destroy
	 * Description: 销毁session时,触发处理函数
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:34:43 
 	*/
 	public static function destroy($id){
 		$sql = "DELETE FROM session WHERE sess_id = '$id'";
		$result = self::$mysql -> query($sql, 'delete');
		return $result == false ?  false : true;
 	}
 	
 	/**
	 * FunctionName: gc
	 * Description: session垃圾回收时,触发处理函数
	 * Permissions: Public
	 * Author: 谢观如
	 * Date: 2012-07-14 15:34:43 
 	*/
 	public static function gc(){
 		$time = time();
 		$sql = "DELETE FROM session WHERE sess_limit_time < $time";
 		self::$mysql -> query($sql, 'delete');
		return true;
 	}
 }
?>