<?php

class kim{

	public function url($path, $args=""){
		$path=trim($path, "/");
		if($args!="")
			$args="/".trim($args, "/");
		if(strstr($path, "/")){
			$url=$path.$args;
		}else{
			$url=$_GET["m"]."/".$path.$args;
		}

		$uri=Some.'/'.$url;//Dome.
		header('location:'.$uri);
	}

}

?>