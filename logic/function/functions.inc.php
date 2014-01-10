<?php
/**
 * @description: 全局函数文件
 * @file: function.inc.php
 * @author: Kim
 * @charset: UTF-8
 * @time: 2012-05-04 14:26:50
 * @version 1.0
**/

/**
 * @name: is_empty
 * @description: 检测变量是否为空
 * @param: mixed 需要判断变量
 * @return: boolean
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function is_empty($var_name){
	$return = FALSE;
	!isset($var_name) && $return = TRUE;
	if(!$return){
		switch(strtolower(gettype($var_name))){
			case 'null' 	:{ $return = TRUE;BREAK; }
			case 'integer' 	:{ $return = FALSE;BREAK; }
			case 'double' 	:{ $return = FALSE;BREAK; }
			case 'boolean' 	:{ $return = FALSE;BREAK; }
			case 'string' 	:{ $return = $var_name==='' ? TRUE : FALSE;BREAK; }
			case 'array' 	:{ $return = count($var_name) > 0 ? FALSE : TRUE;BREAK; }
			case 'object' 	:{ $return = $var_name===null ? TRUE : FALSE;BREAK; }
			case 'resource' :{ $return = $var_name===null ? TRUE : FALSE;BREAK; }
			default 		:{ $return = TRUE; }
		}
	}
	return $return;
}

/**
 * @name: is_type
 * @description: 检测变量类型是否为指定
 * @param: mixed 需要判断变量
 * @param: string 判断的类别
 * @return: boolean
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function is_type($var_name, $var_type){
	$return = FALSE;
	$var_name_resource_type = NULL;
	$var_name_type = strtolower(gettype($var_name));
	$var_name_type == 'resource' && $var_name_resource_type = strtolower(get_resource_type($var_name));
	$var_typeType = strtolower(gettype($var_type));
	if($var_typeType == 'array'){
		if(count($var_type) > 0){
			foreach($var_type as $key => $Val){
				$var_type[$key] = strtolower($Val);
			}
		}
		$return = in_array($var_name_type, $var_type, TRUE) ? TRUE : FALSE;
		(!$return && !is_empty($var_name_resource_type)) && $return = in_array($var_name_type.'-'.$var_name_resource_type, $var_type, TRUE) ? TRUE : FALSE;
	}
	$var_typeType == 'string' && $return = ($var_name_type == strtolower($var_type) || $var_name_type.'-'.$var_name_resource_type == strtolower($var_type)) ? TRUE : FALSE;
	return $return;
}

/**
 * @name: is_exists
 * @description: 判断是否存在[变量、类、接口、类方法、函数、文件、路径]
 * @param: mixed 需要判断变量
 * @param: string 检测类型 default[var]
 * @param: object 类对象 default[NULL]
 * @return: boolean
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function is_exists($var_name, $var_type='var', $object=NULL){
	$return = FALSE;
	switch(strtolower(trim($var_type))){
		case 'var' 		: { $return = isset($var_name) ? TRUE : FALSE; BREAK;}
		case 'file' 	: { $return = file_exists($var_name) ? TRUE : FALSE; BREAK;}
		case 'function' : { $return = function_exists($var_name) ? TRUE : FALSE; BREAK;}
		case 'class' 	: { $return = class_exists($var_name) ? TRUE : FALSE; BREAK;}
		case 'interface': { $return = interface_exists($var_name) ? TRUE : FALSE; BREAK;}
		case 'method' 	: { $return = method_exists($object, $var_name) ? TRUE : FALSE; BREAK;}
		case 'dir' 		: {
							$return = is_dir($var_name) ? TRUE : FALSE;
							$return && $return = is_exists($var_name, 'file', $object);
							BREAK;
						}
	}
	return $return;
}

/**
 * @name: is_include
 * @description: 文件是否被引入
 * @param: string 引入的文件全路径
 * @return: boolean
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function is_include($include_file){
	return in_array($include_file, get_included_files(), TRUE) ? TRUE : FALSE;
}

/**
 * @name: get_cur_time
 * @description: 获取当前时间
 * @param: boolean 返回是否字符串[FALSE]
 * @return: array
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_cur_time($is_string=FALSE){
	$cur_time = microtime();
	return $is_string ? $cur_time : array(doubleval(substr($cur_time, 0, 10)), intval(substr($cur_time, 11, 10)));
}

/**
 * @name: time_array
 * @description: 字符串转换成数组
 * @param: string 时间字符串[microtime]
 * @return: array
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function time_array($string){
	return array(doubleval(substr($string, 0, 10)), intval(substr($string, 11, 10)));
}

/**
 * @name: time_diff
 * @description: 计算时间差
 * @param: array 开始时间
 * @param: array 结束时间按
 * @param: integer 取小数点位
 * @return: double
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function time_diff($time_form, $time_to=NULL, $point=10){
	$return = 0.0;
	is_empty($time_to) && $time_to = get_cur_time();
	is_type($time_form, 'string') && $time_form = time_array($time_form);
	if(is_empty($time_form) || !is_type($time_form, 'array')) return FALSE;
	$return = ($time_to[0]-$time_form[0])+($time_to[1]-$time_form[1]);
	return sprintf("%.".$point."f", $return);
}

/**
 * @name: var_string
 * @description: 将变量转换成字符串
 * @param: mixed 变量
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function var_string($value){
	switch(strtolower(gettype($value))){
		case 'null' 	: { return NULL; BREAK; }
		case 'integer' 	: { settype($value, 'string'); return $value; BREAK;}
		case 'double' 	: { settype($value, 'string'); return $value; BREAK; }
		case 'string' 	: { return '"'.$value.'"'; BREAK;}
		case 'array' 	: {
			$return = '';
			$i = 0;
			foreach($value as $key => $val){
				$return .= ($return == '' ? '' : ', ');
				$return .= (gettype($key) == 'integer' ? $key : '"'.$key.'"');
				$return .= ' => ';
				$tmp_type = gettype($val);
				$return .= ($tmp_type == 'array' ? var_string($val) : ($tmp_type == 'integer' ? $val : '"'.$val.'"'));
			}
			return 'Array('.$return.')';
		}
		case 'object' 	:{ settype($value, 'string'); return $value; }
		case 'resource' :{ settype($value, 'string'); return $value; }
		case 'boolean' 	:{ return $value ? 'TRUE' : 'FALSE'; }
	}
	return TRUE;
}

/**
 * @name: get_rand_string
 * @description: 获取随机字符串
 * @param: integer 随机字符的长度
 * @param: integer 随机字符的模式 default[7],1-15
 * @param: boolean 是否去除字符 default[FALSE] O,o,0
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_rand_string($leng, $type=7, $dark=FALSE){
	$tmp_array = array(
				'1' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
				'2' => 'abcdefghijklmnopqrstuvwxyz',
				'4' => '0123456789',
				'8' => '~!@$&()_+-=,./<>?;\'\\:"|[]{}`'
			);
	$return = $target_string = '';
	$array = array();
	$bin_string = decbin($type);
	$bin_leng  = strlen($bin_string);
	for($i = 0; $i < $bin_leng; $i++) if($bin_string{$i} == 1) $array[] = pow(2, $bin_leng - $i - 1);
	if(in_array(1, $array, TRUE)) $target_string .= $tmp_array['1'];
	if(in_array(2, $array, TRUE)) $target_string .= $tmp_array['2'];
	if(in_array(4, $array, TRUE)) $target_string .= $tmp_array['4'];
	if(in_array(8, $array, TRUE)) $target_string .= $tmp_array['8'];	
	$target_leng = strlen($target_string);
	mt_srand((double)microtime()*1000000);
	while(strlen($return) < $leng){
		$tmp_string = substr($target_string, mt_rand(0, $target_leng), 1);
		$dark && $tmp_string = (in_array($tmp_string, array('0', 'O', 'o'))) ? '' : $tmp_string;
		$return .= $tmp_string;
	}
	return $return;
}

/**
 * @name: en_de_code
 * @description: 加密解密数据
 * @param: string 被加密or解密的字符串
 * @param: string 加密or解密关键key default[123456]
 * @param: integer 加密or解密 default[1],1-加密,2-解密
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function en_de_code($string, $key='', $types=1){
	($key = trim($key)) && is_empty($key) && $key = '123456';
	$key = md5($key);
	$key_leng = strlen($key);
	if($key_leng == 0) return FALSE;
	$string = $types != 1 ? base64_decode($string):substr(md5($string.$key), 0, 8).$string;
	$stringLeng = strlen($string);
	$rndkey = $box = array();
	$result = '';
	for($i = 0; $i <= 255; $i++){
		$rndkey[$i] = ord($key[$i % $key_leng]);
		$box[$i] = $i;
	}
	for($j = $i = 0; $i < 256; $i++){
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $stringLeng; $i++){
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($types != 1){
		if(substr($result,0,8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		}else{
			return '';
		}
	}else{
		return str_replace('=', '', base64_encode($result));
	}
}

/**
 * @name: icon_var
 * @description: 转换字符串编码
 * @param: string 被转换的原字符串
 * @param: string 被转换的类型 default[gb2312,utf8,i]
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function icon_var($string, $type='gb2312,utf8,i'){
	$type_array = explode(',', $type);
	$type_leng = count($type_array);
	if($type_leng != 2 && $type_leng != 3) return FALSE;
	$form = strtoupper(trim($type_array[0]));
	$to = strtoupper(trim($type_array[1]));
	$prame = '';
	$type_leng == 3 && ($prame = '//'.(strtoupper(trim($type_array[2]))=='t'?'TRANSLIT':'IGNORE'));
	return iconv($form, $to.$prame, $string);
}

/**
 * @name: get_var_get
 * @description: GET方式获取表单数据
 * @param: string 表单name参数名称
 * @param: boolean 是否过滤字符串安全
 * @return: mixed
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_var_get($var_name, $is_filter=TRUE){
	$return = isset($_GET[$var_name]) ? $_GET[$var_name] : NULL;
	if($is_filter && !is_empty($return)) $return = filter_string($return);
	return $return;
}

/**
 * @name: get_var_post
 * @description: POST方式获取表单数据
 * @param: string 表单name参数名称
 * @param: boolean 是否过滤字符串安全
 * @return: mixed
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_var_post($var_name, $is_filter=TRUE){
	$return = isset($_POST[$var_name]) ? $_POST[$var_name] : NULL;
	if($is_filter && !is_empty($return)) $return = filter_string($return);
	return $return;
}

/**
 * @name: get_var_value
 * @description: 获取表单数据(GET 和 POST)
 * @param: string 表单name参数名称
 * @param: boolean 是否过滤字符串安全
 * @param: boolean 是否优先获取POST
 * @return: mixed
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_var_value($var_name, $is_filter=TRUE, $is_post=TRUE){
	$return = NULL;
	if($is_post){
		$return = get_var_post($var_name, $is_filter);
		$return === NULL && $return = get_var_get($var_name, $is_filter);
	}else{
		$return = get_var_get($var_name, $is_filter);
		$return === NULL && $return = get_var_post($var_name, $is_filter);
	}
	return $return;
}

/**
 * @name: get_ip
 * @description: 获取客户端IP地址
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_ip(){
	if($_SERVER['REMOTE_ADDR']) return $_SERVER['REMOTE_ADDR'];
	elseif($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) return $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
	elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) return $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
	elseif($HTTP_SERVER_VARS["REMOTE_ADDR"]) return $HTTP_SERVER_VARS["REMOTE_ADDR"];
	elseif(getenv("HTTP_X_FORWARDED_FOR")) return getenv("HTTP_X_FORWARDED_FOR");
	elseif(getenv("HTTP_CLIENT_IP")) return getenv("HTTP_CLIENT_IP");
	elseif(getenv("REMOTE_ADDR")) return getenv("REMOTE_ADDR");
	else return '127.0.0.1';
}

/**
 * @name: time_int
 * @description: 转换时间成整形
 * @param: string 被转换时间
 * @return: integer
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function time_int($time_string){
	$return = FALSE;
	if(preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/', $time_string, $match)){
		if(!isset($match[3])) return FALSE;
		$return = mktime($match[4], $match[5], $match[6], $match[2], $match[3], $match[1]);
	}
	return $return;
}

/**
 * @name: get_var_name
 * @description: 获取变量的名字[引用变量返回数组]
 * @param: mixed 变量的值
 * @param: mixed 变量的作用域 default[GLOBALS]
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_var_name(&$var_name, $scope=NULL){
	$return = FALSE;
	is_empty($scope) && $scope = $GLOBALS;
	$tmp = $var_name;
	$var_name = 'varname_isexists_'.mt_rand();
	$return = array_keys($scope, $var_name, TRUE);
	$var_name = $tmp;
	(is_type($return, 'array') && count($return) == 1) && $return = $return[0];
	return $return;
}

/**
 * @name: file_size_string
 * @description: 计算文件格式单位
 * @param: integer 被转换的数字
 * @param: integer 小数点位数 default[2]2位小数点
 * @param: integer 进制单位大小 default[1024]
 * @param: integer 取整类型 default[0]0-四舍五入,1-向下取整,2-向上取整
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function file_size_string($file_size, $decim=2, $units=1024, $val_crf=0){
	$tmp_array = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	$i = 1;
	$j = count($tmp_array);
	$decim_pow = pow(10, $decim);
	while($file_size >= pow($units, $i) && $i <= $j) ++$i;
	if($val_crf == 2){
		return ceil(($file_size/pow($units, $i-1))*$decim_pow)/$decim_pow.' '.$tmp_array[$i-1];
	}else if($val_crf == 1){
		return round(($file_size/pow($units, $i-1))*$decim_pow)/$decim_pow.' '.$tmp_array[$i-1];
	}else{
		return floor(($file_size/pow($units, $i-1))*$decim_pow)/$decim_pow.' '.$tmp_array[$i-1];
	}
}

/**
 * @name: hex_bin
 * @description: 十六进制转二进制
 * @param: string 被转换字符串
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function hex_bin($string){
	$return = '';
	$length = strlen($string);
	for($i = 0; $i < $length; $i += 2) $return .= pack('C', hexdec(substr($string, $i, 2)));
	return $return;
}

/**
 * @name: long_ip
 * @description: 长整型转成ip地址
 * @param: integer 数字
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function long_ip($ip_long){
	return long2ip($ip_long);
}

/**
 * @name: ip_long
 * @description: ip地址转成长整型
 * @param: string ip地址
 * @return: integer
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function ip_long($ip_string){
	$return = 0;
	$tmp_array = explode('.', $ip_string);
	foreach($tmp_array as $key => $val) $return += intval($val)*pow(256, abs($key-3));
	return $return;
}

/**
 * @name: load_file
 * @description: 引入文件
 * @param: string 引入文件名称
 * @param: boolean 引入是否必须 [default-TRUE]
 * @param: boolean 引入类型 [default-TRUE(include)]
 * @param: boolean 引入是否唯一 [default-TRUE]
 * @param: string 被声明的全局变量[,分隔符] [default]
 * @return: boolean
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function load_file($file_url, $is_must=TRUE, $is_include=TRUE, $is_once=TRUE, $global_var=''){
	if(!is_exists($file_url, 'file')){
		return $is_must ? FALSE : TRUE;
	}
	!is_empty($global_var) && eval('global '.$global_var.';');
	if($is_include){
		$is_once && include_once($file_url);
		!$is_once && include($file_url);
	}else{
		$is_once && require_once($file_url);
		!$is_once && require($file_url);
	}
	return TRUE;
}

/**
 * @name: shift_right
 * @description: 无符号右移位
 * @param: integer 被移动值
 * @param: integer 移动的位数
 * @return: integer
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function shift_right($var_int, $move_int){
	!defined('STR_PAD_LEFT') && define('STR_PAD_LEFT', 0);
	if($move_int <= 0) return $var_int;
	if($move_int >= 32) return 0;
	$var_int = decbin($var_int);
	$var_int_leng = strlen($var_int);
	if($var_int_leng > 32){
		$var_int = substr($var_int, $var_int_leng-32, 32);
	}elseif($var_int_leng < 32){
		$var_int = str_pad($var_int, 32, '0', STR_PAD_LEFT);
	}
	return bindec(str_pad(substr($var_int, 0, 32-$move_int), 32, '0', STR_PAD_LEFT));
}

/**
 * @name: shift_left
 * @description: 无符号左移位
 * @param: integer 被移动值
 * @param: integer 移动的位数
 * @return: integer
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function shift_left($var_int, $move_int){
	!defined('STR_PAD_LEFT') && define('STR_PAD_LEFT', 0);
	!defined('STR_PAD_RIGHT') && define('STR_PAD_RIGHT', 1);
	if($move_int <= 0) return $var_int;
	if($move_int >= 32) return 0;
	$var_int = decbin($var_int);
	$var_int_leng = strlen($var_int);
	if($var_int_leng > 32){
		$var_int = substr($var_int, $var_int_leng-32, 32);
	}elseif($var_int_leng < 32){
		$var_int = str_pad($var_int, 32, '0', STR_PAD_LEFT);
	}
	return bindec(str_pad(substr($var_int, $move_int), 32, '0', STR_PAD_RIGHT));
}

/**
 * @name: get_array_num
 * @description: 返回数组的深度数
 * @param: array 检测的数组
 * @param: integer 当前计算深度 default[1]
 * @return: integer
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_array_num($array, $i=1){
	if(!is_type($array, 'array')) return FALSE;
	$i = $i < 1 ? 1 : $i;
	$return = $i;
	if(!is_empty($array)){
		foreach($array as $val){
			if(is_type($val, 'array')){
				$return = max($i, $return, get_array_num($val, $i+1));
			}
		}
	}
	return $return;
}

/**
 * @name: get_array_sum
 * @description: 返回数组的全部个数
 * @param: array 检测的数组
 * @return: integer
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function get_array_sum($array){
	if(!is_type($array, 'array')) return FALSE;
	$return = 1;
	if(!is_empty($array)){
		foreach($array as $val){
			if(is_type($val, 'array')){
				$return += get_array_sum($val);
			}
		}
	}
	return $return;
}

/**
 * @name: xml_array
 * @description: XML转成数组
 * @param: string Xml字符串
 * @param: boolean 是否启用 attribute default[FALSE]
 * @return: array
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function xml_array($xml_string, $attribute=FALSE){
	$return = array();
	$search = $attribute ? '|<((\S+)(.*))\s*>(.*)</\2>|Ums' : '|<((\S+)()).*>(.*)</\2>|Ums';
	$xml_string = preg_replace('|>\s*<|', ">\n<", $xml_string);
	$xml_string = preg_replace('|<\?.*\?>|', '', $xml_string);
	$xml_string = preg_replace('|<(\S+?)(.*)/>|U', '<$1$2></$1>', $xml_string);
	if(!preg_match_all($search, $xml_string, $match) || is_empty($match[1])) return FALSE;
	foreach($match[1] as $key => $val){
		if(!isset($return[$val])) $return[$val] = array();
		$return[$val][] = xml_array($match[4][$key], $attribute);
	}
	return $return;
}

/**
 * @name: sql_normalize
 * @description: 处理sql语句条件
 * @param: string 处理的sql
 * @return: string
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function sql_normalize($sql){
	$sql = preg_replace("/\\/\\*.*\\*\\//sU", '', $sql); 						// remove multiline comments
	$sql = preg_replace("/([\"'])(?:\\\\.|\"\"|''|.)*\\1/sU", "{}", $sql); 		// remove quoted strings
	$sql = preg_replace("/(\\W)(?:-?\\d+(?:\\.\\d+)?)/", "\\1{}", $sql); 		// remove numbers
	$sql = preg_replace("/(\\W)null(?:\\Wnull)*(\\W|\$)/i", "\\1{}\\2", $sql); 	// remove nulls
	$sql = str_replace(array("\\n", "\\t", "\\0"), ' ', $sql); 					// replace escaped linebreaks
	$sql = preg_replace("/\\s+/", ' ', $sql); 									// remove multiple spaces
	$sql = preg_replace("/ (\\W)/", "\\1", $sql); 								// remove spaces bordering with non-characters
	$sql = preg_replace("/(\\W) /", "\\1", $sql); 								// --,--
	$sql = preg_replace("/\\{\\}(?:,?\\{\\})+/", "{}", $sql); 					// repetitive {},{} to single {}
	$sql = preg_replace("/\\(\\{\\}\\)(?:,\\(\\{\\}\\))+/", "({})", $sql); 		// repetitive ({}),({}) to single ({})
	$sql = strtolower(trim($sql, " \t\n)(")); 									// trim spaces and strolower
	return $sql;
}

/**
 * @name: filter_string
 * @description: 过滤非安全字符
 * @param: mixed 被过滤的原字符串或数组
 * @return: mixed
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function filter_string($string){
	if(is_empty($string)) return '';
	if(is_array($string)){
		foreach($string as $key => $val) $string[$key] = filter_string($val);
		return $string;
	}else{
		$search = array("'<script[^>]*?>.*?</script>'si", "'<[\/\!]*?[^<>]*?>'si", "'([\r\n])[\s]+'", "'&(quot|#34);'i", "'&(amp|#38);'i", "'&(lt|#60);'i", "'&(gt|#62);'i", "'&(nbsp|#160);'i", "'&(iexcl|#161);'i", "'&(cent|#162);'i", "'&(pound|#163);'i", "'&(copy|#169);'i", "'&#(\d+);'e");
		$replace = array("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");
		return trim(addslashes(nl2br(stripslashes(preg_replace($search, $replace, $string)))));
	}
}

/**
 * @name: up_file
 * @description: 上传文件
 * @param: array 被上传的文件数组信息
 * @param: string 上传文件的目录路径和文件名称
 * @param: array 允许、不允许上传的文件类型[NULL不限制,array('jpg|png', 'php')]
 * @param: integer 允许上传的大小[字节,-1不限制]
 * @param: string 上传文件的目录路径和文件名称备用
 * @param: string 上传文件的后缀名[default无,AUTO-自动带点]
 * @return: string[A-不允许类型,B-拒绝类型文件,S-超过大小,F-文件存在,T-备用文件存在,N(false)-失败,Y-成功]
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function up_file($files, $dest_file, $allow=NULL, $size=-1, $filet=NULL, $annx=NULL){
	if(is_empty($files) || !is_type($files, 'array'))return FALSE;
	if(is_empty($dest_file) || !is_type($dest_file, 'string'))return FALSE;
	$up_size = intval($files['size']);
	$up_type = trim($files['type']);
	$up_name = trim($files['name']);
	$up_tmp_name = trim($files['tmp_name']);
	$up_name_annx = strtolower(substr($up_name, strrpos($up_name, '.')+1));
	if(!is_empty($annx)){
		if(strtoupper(substr($annx, 0, 4)) == 'AUTO'){
			if($annx{4} == '+'){
				$return = '.'.$up_name_annx.substr($annx, 5);
			}else{
				$return = '.'.$up_name_annx;
			}
			$dest_file .= $return;
		}else{
			$dest_file .= $annx;
		}
	}
	if(file_exists($dest_file)){
		if(is_empty($filet)){
			return 'F';
		}else{
			if(file_exists($filet) && $dest_file != $filet) return 'T';
			$dest_file = $filet;
		}
	}
	if($size >= 0 && $up_size > $size) return 'S';
	if(!is_empty($allow)){
		if(isset($allow[0]) && !is_empty($allow[0])){	//允许
			if($allow[0] != '*'){
				$tmp = explode('|', $allow[0]);
				$rs = FALSE;
				if(!is_empty($tmp)){
					foreach($tmp as $val){
						if($val=='*' || in_array($up_name_annx, $tmp)){$rs = TRUE; break;}
					}
				}
				if(!$rs){return 'A';}
			}
		}
		if(isset($allow[1]) && !is_empty($allow[1])){	//拒绝
			$tmp = explode('|', $allow[1]);
			$rs = FALSE;
			if(!is_empty($tmp)){
				foreach($tmp as $val){
					if(in_array($up_name_annx, $tmp)){$rs = TRUE; break;}
				}
			}
			if($rs){return 'B';}
		}
	}
	if(@move_uploaded_file($up_tmp_name, $dest_file)){
		if(isset($return)){
			return $return;
		}else{
			return 'Y';
		}
	}else{
		return 'N';
	}
}

/**
 * @name: object_to_array
 * @description: 对象转成数组
 * @param: object 实例化的对象
 * @return: array
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function object_to_array($object){
    $array = (Array)$object;
    foreach($array as $key => $val){
    	unset($array[$key]);
    	$array[preg_replace('/^.+\0/', '', $key)] = $val;
    }
    return $array;
}

/**
 * @name: delete_html
 * @description: 删除html标签
 * @param: String 内容
 * @return: String
 * @author: Kim
 * @create: 2012-05-11 18:26:50
 **/
function delete_html($document)
{
	$document = trim($document);
	if (strlen($document) <= 0) {
		return $document;
	}
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
			"'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记
			"'([\r\n])[\s]+'",                // 去掉空白字符
			"'&(quot|#34);'i",                // 替换 HTML 实体
			"'&(amp|#38);'i",
			"'&(lt|#60);'i",
			"'&(gt|#62);'i",
			"'&(nbsp|#160);'i"
	);                    // 作为 PHP 代码运行
	$replace = array ("",
			"",
			"\1",
			"\"",
			"&",
			"<",
			">",
			" "
	);
	$document = preg_replace ($search, $replace, $document);
	
	
	$document = preg_replace("/[rn]{1,}/isU","
			rn",$document);
	
	$preg = '/<div.*>/';
	
	preg_match($preg, $document, $arr);
	
	return strip_only_tags($document, array('div'));
}

/**
 * @name strip_only_tags
 * @description: 删除指定的html标签
 * @param string $str
 * @param array $tags 形如:array('a','div')
 * @param boolean $stripContent 是否删除标签中的内容
 * @author Kim
 * @create: 2012-05-11 18:26:50
 */
function strip_only_tags($str, $tags, $stripContent = FALSE) {
	$content = '';     
	if (!is_array($tags)) {
		$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));     
		if (end($tags) == '') {
			array_pop($tags);
		}
	}     
	foreach($tags as $tag) {
		if ($stripContent) {
			$content = '(.+<!--'.$tag.'(-->|\s[^>]*>)|)';
		}      
		$str = preg_replace('#<!--?'.$tag.'(-->|\s[^>]*>)'.$content.'#is', '', $str);
	}     
	return $str;
}

/**
 * FunctionName: get_referer
 * Description: 获取客户来源
 * Author: Kim
 * Return: string
 * Date: 2012-06-07 10:02:56 
**/
function get_referer(){
	$referer = false;
	if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){
		$referer = $_SERVER['HTTP_REFERER'];
	}
	return $referer;
}

/**
 * FunctionName: terminal
 * Description: 执行服务器命令函数
 * Author: Kim
 * Return: string
 * Date: 2012-06-15 16:02:56 
**/	
function terminal($command) {
	//system      
	if(function_exists('system'))      
	{          
		ob_start();          
		system($command , $return_var);          
		$output = ob_get_contents();          
		ob_end_clean();      
	}      
	//passthru      
	else if(function_exists('passthru'))      
	{          
		ob_start();          
		passthru($command , $return_var);          
		$output = ob_get_contents();          
		ob_end_clean();      
	}      
	//exec      
	else if(function_exists('exec'))      
	{          
		exec($command , $output , $return_var);          
		$output = implode("\n" , $output);      
	}       
	//shell_exec      
	else if(function_exists('shell_exec'))      
	{          
		$output = shell_exec($command) ;     
	} else {          
		$output = 'Command execution not possible on this system';          
		$return_var = 1;      
	}       
	return array('output' => $output , 'status' => $return_var);  
}


/**
 * FunctionName: myjson
 * Description: json_encode的时候中文不被编码
 * Author: xiaochengcheng
 * Return: json
 * Date: 2013-8-6 11:56:31
**/	
function myjson($code) {
	$code = json_encode(urlencodeAry($code));
	return urldecode($code);
}

function urlencodeAry($data) {
	if(is_array($data)) {
		foreach($data as $key=>$val) {
			$data[$key] = urlencodeAry($val);
		}
		return $data;
	} else {
		return urlencode($data);
	}
}  

?>