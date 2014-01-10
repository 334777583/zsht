<?php
/**
 * FileName: userlogin.class.php
 * Description: 登录概况
 * Author: xiaochengcheng
 * Date: 2013-4-8 14:23:40
 * Version: 1.00
 **/
class pingtai{
	
	
	
	//用户每日平均在线时长
	public function returnpingtai(){
		$mer = 'ASD123456@#$%$';
		$name = get_var_value('name');
		$time = get_var_value('time');
		$sige = get_var_value('sige');
		//echo MD5($name.$time.$mer);
		if($name && $time && $sige){
			if(MD5($name.$time.$mer) == $sige){
				$main = D("game_base");
				$list = $main-> table("generatekey")->where('g_name ="'.$name.'"')->find();
				if($list){
					echo json_encode($list);
				}else{
					echo '0';
				}	
			}else{
				echo '0';
			}	
		}else{
			echo '0';
		}
		exit();
	}
	

}