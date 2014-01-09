<?php


function __autoload($classname){
	if(!strstr($classname,'auto')){
		return true;
	}
	if(is_file(AClass.$classname.'.class.php')){
		require_once(AClass.$classname.'.class.php');
		if (!class_exists($classname, false)) {
			 common::printlog($classname.'Class not found');
		}
	}else{
		 common::printlog('./class in this dir can not find file :'.$classname.'.class.php');
	}
}

function D($db){
	$obj = new DB($db,HOST,USER,PASS);
	return $obj;
}

//拓展数据库功能
function F($db,$h,$u,$p,$port=3306){	
	$obj = new DB($db,$h,$u,$p,$port);
	return $obj;
}

?>