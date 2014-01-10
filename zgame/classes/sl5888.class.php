<?php
/**
 * Copyright: 石佳佰林 2012
 * FileName: sl5888.class.php
 * Description: 通用应用扩展类
 * Author: 陈 飞
 * Date: 2012年05月05日 10:13
 * Version: 1.00
 **/

class Sl5888 {

	/**
	 * FunctionName: setkookie_array_base
	 * Description: 追加一个值存储到cookie序列化数组
	 * Author: 陈 飞
	 * Parameter：Integre 要存入的值
	 * Parameter：String cookie的名字
	 * Date: 2012年03月03日 10:19
	 **/
	public function setkookie_array_base($cookie_value = NULL, $cookie_name = NULL){
		if(empty($cookie_value) || empty($cookie_name)) return FALSE;
		$res = $this->getkookie_array_base($cookie_name); //获取cookie
		if(!$res){ //没有存过
			$cookie_value_arr 	= array($cookie_value);
			$id_arr_str	= serialize($cookie_value_arr);
			setcookie($cookie_name, $id_arr_str);
		}else{//有存过
			if(!in_array($cookie_value, $res)){
				$res[] = $cookie_value;
				$res_str = serialize($res);
				setcookie($cookie_name, $res_str, time() + 3600 * 24 * 7, '/', DOMAIN);
			}
		}
	}
	
	/**
	 * FunctionName: getkookie_array_base
	 * Description: 获取cookies中的ids数组 
	 * Author: 陈 飞
	 * Parameter：String cookie的名字
	 * Return: Array
	 * Date: 2012年03月03日 10:19
	 **/
	public function getkookie_array_base($cookie_name = NULL){
		$arr_str = $_COOKIE[$cookie_name];
		if(empty($arr_str)){
			return FALSE;
		}else{
			return array_unique(unserialize($arr_str));
		}
	}
	

}