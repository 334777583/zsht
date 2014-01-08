<?php
/**
 * FileName: common.class.php
 * Description: 路由文件
 * Author: kim
 * Date: 2013-4-7 10:34:08
 * Version: 1.00
 **/
class common{

	static function parseUrl(){
		if (isset($_SERVER['PATH_INFO'])){
			$pathinfo = explode('/', trim($_SERVER['PATH_INFO'], "/"));
			$_GET['m'] = (!empty($pathinfo[0]) ? $pathinfo[0] : 'index');
			array_shift($pathinfo); 
			$_GET['a'] = (!empty($pathinfo[0]) ? $pathinfo[0] : 'index');
			array_shift($pathinfo); 
			// echo count($pathinfo);
			if(count($pathinfo) < 2 && count($pathinfo)>0){
				$_GET[$pathinfo[0]]='';
			}else{
				for($i=0; $i<count($pathinfo); $i+=2){
					if(!isset($pathinfo[$i+1])){
						$_GET[$pathinfo[$i]]='';
					}else{
						$_GET[$pathinfo[$i]]=$pathinfo[$i+1];
					}
				}
			}
		}else{	
			$_GET["m"]= (!empty($_GET['m']) ? $_GET['m']: 'index');    //默认是index模块
			$_GET["a"]= (!empty($_GET['a']) ? $_GET['a'] : 'index');   //默认是index动作

			if($_SERVER["QUERY_STRING"]){
				$m=$_GET["m"];
				unset($_GET["m"]);  
				$a=$_GET["a"];
				unset($_GET["a"]); 
				$query=http_build_query($_GET);
				$url=$_SERVER["SCRIPT_NAME"]."/{$m}/{$a}/".str_replace(array("&","="), "/", $query);
				header("Location:".$url);
			}	
		}
	}

	static function writeFile($str,$mode='a+'){
		if(! ISWRITE){
			return true;
		}
		$oldmask = @umask(0);
		$log = LogPath.'log_'.date('Ymd').'.log';
		$fp = @fopen($log,$mode);
		@flock($fp, 3);
		if(!$fp){
			Return false;
		}else{
			@fwrite($fp,$str);
			@fclose($fp);
			@umask($oldmask);
			Return true;
		}
	}

	static function printlog($log){
		if(! DEBUG){
			return true;
		}
		die($log);
	}

	private function get_ip(){
		if($_SERVER['REMOTE_ADDR']) return $_SERVER['REMOTE_ADDR'];
		elseif($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) return $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
		elseif($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) return $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
		elseif($HTTP_SERVER_VARS["REMOTE_ADDR"]) return $HTTP_SERVER_VARS["REMOTE_ADDR"];
		elseif(getenv("HTTP_X_FORWARDED_FOR")) return getenv("HTTP_X_FORWARDED_FOR");
		elseif(getenv("HTTP_CLIENT_IP")) return getenv("HTTP_CLIENT_IP");
		elseif(getenv("REMOTE_ADDR")) return getenv("REMOTE_ADDR");
		else return '127.0.0.1';
	}

	static function referer(){
		if(isset($_SERVER['HTTP_REFERER'])){
// 			echo $_SERVER['HTTP_REFERER'];
		}
	}

	private function headers(){
		header('HTTP/1.1 404 Not Found');
		header("status: 404 Not Found"); 
		exit();
	}

	static function init(){
		$_GET["m"]= (!empty($_GET['m']) ? $_GET['m']: 'index');
		$_GET["a"]= (!empty($_GET['a']) ? $_GET['a'] : 'index');

		$file = Control.$_GET["m"].'.class.php';
		if (class_exists($file)) {
			 common::printlog('Class not found');
		}else{
			require $file;
			
			$obj = new $_GET["m"]();

			if(method_exists($_GET["m"],'init')){
				$obj -> init();
			}
			if(!method_exists($_GET["m"],$_GET['a'])){
				common::printlog('class not find this function '.$_GET['a']);
			}else{
				if($_GET['a'] != $_GET["m"]){
					$obj -> $_GET['a']();
				}
			}
			
		}
	}
}




?>