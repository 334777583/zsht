<?php

class index{
	
	
// 	function index(){
// 		//echo $_GET['asdf'];
// 		echo '2';
// 	}
	

	function index2(){
		session_start();
		print_r(unserialize($_SESSION['user2']));
		//echo $_GET['fuck'];
		$obj = D('game_base');
// 		print_r($obj);
// 		$obj ->url('index/test');
		print_r($obj -> table('user') -> select());
		// print_r($obj);
	}
	
	
	/**
	 * 统计登录用户地区分布
	 */
	public function statArea() {
		$ip = get_var_value('ip');
		$type = get_var_value('type');
		if($ip) {
			$point = D('game'.$ip);
		
			$obj = $point -> table('ip') -> select();
			
			if($obj != '') {
				$result = array();
				$stat = array();
				$sum = count($obj);
				$o = new autoipsearchdat();
				// echo $o->findIp('218.19.227.180');
				foreach($obj as $item) {
					$area = $o->findIp($item['i_ip']);
					$result[$item['i_ip']] = $area;
					
					if(isset($stat[$area])) {
						$stat[$area] ++;
					}else {
						$stat[$area] = 1;
					}
				}
				header("Content-type: text/html; charset=utf-8");
				if($type) {
					echo '----------所有用户地区信息----------<br/>';
					echo '总数:  ' . $sum . '<br/>';
					foreach($result as $ip => $area) {
						echo $ip .':   ' . $area . '<br/>';
					}
				} else {
					echo '----------地区统计信息----------<br/>';
					echo '总数:  ' . $sum . '<br/>';
					foreach($stat as $area => $sum) {
						echo $area .':' . $sum . '<br/>';
					}
				}
			}
		} else {
			echo 'no ip!';	
		}
	}
}

?>