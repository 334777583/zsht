<?php
/**
 * FileName: userrole.class.php
 * Description:创角分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-6-6 17:41:50
 * Version:1.00
 */
class userrole{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400101', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}

	public function getshishi(){
		$ip = get_var_value('sip');
		$point = D('game'.$ip);
		global $t_conf;

		$obj = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		
		$result = array();
		$nows = strtotime(date('Y-m-d').' 00:00:00');
		$total_login = $obj->fquery("SELECT COUNT(id) cid FROM game_user WHERE LastLogoutTime >= {$nows}");
		
		$online = $obj->fquery("SELECT count(GUID) count from player_table where bOnline=1 and ServerId = {$ip}");
		// $cnum = $obj->fquery("SELECT sum(RMB) count from player_table where RMB > 0 and ServerId = {$ip} GROUP BY AccountId ");
		$cnum = $obj->fquery("SELECT count(GUID) count from player_table where RMB > 0 and ServerId = {$ip} GROUP BY AccountId ");
		if(is_array($cnum)){
			$result[0]['cnum'] = count($cnum);
		}else{
			$result[0]['cnum'] = 0;
		}
		$num = $obj->fquery("SELECT count(GUID) count from player_table where RMB > 0 and ServerId = {$ip}");
		
		$summoney = $obj->fquery("SELECT SUM(RMB) count FROM player_table where ServerId = {$ip}");
		if(!empty($summoney)){
			$result[0]['summoney'] = $summoney[0]['count'];
		}else{
			$result[0]['summoney'] = 0;
		}
		$jiaose = $obj->fquery("SELECT COUNT(id) count FROM game_user WHERE Createtime > {$nows}");
		
		$result[0]['login'] = $total_login[0]['cid'];
		$result[0]['online'] = $online[0]['count'];
		$result[0]['num'] = $num[0]['count'];
		$result[0]['jiaose'] = $jiaose[0]['count'];
		echo json_encode($result);
	}
	/*
	//读取日记
	private function getLog($file){
		$file1_arr1 = $result1 = array();
		$file1_arr = explode("\n", $file);
		for ($i=0; $i <count($file1_arr)-1 ; $i++) { 
			$file1_arr[$i] = substr($file1_arr[$i], 22);
		}
		foreach ($file1_arr as $k => $v) {
			$a = explode('},{', $v);
			foreach ($a as $key => $value) {
				$b = explode(',', $value);
				foreach ($b as $num => $item) {
					if ($num == 0) {
						$result[$k]['playid'] = substr($item, strpos($item, ':')+2,-1);
					}
					if ($num == 1) {
						$result[$k]['time'] = substr($item, strpos($item, ':')+2,-3);
					}
				}
			}
		}
		return $result;
	}
	*/
	/**
	 * 获取行为分析数据
	 */
	public function getRole() {
	
		$ip = get_var_value('sip');
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		//$date = substr(file_get_contents(TPATH."/log-type-9.log"), 1,10);
		// $file2 = count($this->getLog(file_get_contents(TPATH."/log-type-10.log")));
		// $file1 = count($this->getLog(file_get_contents(TPATH."/log-type-9.log")));
		// $point = D('game'.$ip);
		// $point->fquery("INSERT INTO createplay(c_csuccess,c_entergame,c_date) VALUES({$file1},{$file2},'{$date}')");
		// die;
		
		if($ip && $startdate && $enddate) {
			$point = D('game'.$ip);
			//$point
			$list = $point -> table('createplay') -> where('c_date >= "'.$startdate .'" and c_date <= "'.$enddate.'"') ->select();
			if($list != '') {
				foreach($list as $k => $item) {
					if($item['c_enter'] != 0 ){
						$cjv = $item['c_csuccess']/$item['c_enter'];							//创角率
						if($cjv < 0) {															//负数设为0
							$cjv = 0;
						}
					}else {
						$cjv = 0;
					}
					if($item['c_csuccess'] != 0) {
						$load = ($item['c_csuccess']-$item['c_entergame'])/$item['c_csuccess'];	 //流失率
						if($load < 0) {
							$load = 0;
						}
					}else {
						$load = 0;
					}
					
					$list[$k]['c_cjv'] = sprintf('%0.2f',$cjv*100).'%';
					$list[$k]['c_load'] = sprintf('%0.2f',$load*100).'%';
				}
			}
			echo json_encode(array('result' => $list));
			exit;
		} else {
			echo '1';
		}
	}
	
}