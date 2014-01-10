<?php
/**
 * FileName: useractive.class.php
 * Description: 用户活跃分析
 * Author: xiaochengcheng
 * Date: 2013-3-18 14:09:01
 * Version: 1.00
 **/
class useractive{
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	/**
	 * 服务器IP
	 * @var String
	 */
	private $ip;
	
	/**
	 * 查询时间
	 * @var String
	 */
	private $date;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00400300", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->date = get_var_value("date") == NULL?date("Y-m-d",strtotime("-14 day")):date("Y-m-d",strtotime(get_var_value("date")));
	}
	
	/**
	 * 获取双周留存信息
	 */
	public function getDouble(){
		$count = 1;						//登录总数
		$result = array();				//结果数值，保存2周内留存百分比数据
		$names = array();				//用户名
		$seven_user = array();			//第七天留存用户
		$eight_fiften_user = array();	//第八天到十五天内的登录用户(去重)
		$obj = D("game".$this->ip);
		$list = $obj->table("user")->where(array("u_date like "=>$this->date."%"))->select();
		if(is_array($list)){
			$count = count($list);
			foreach($list as $user){
				$names[$user["u_username"]] = 0;	//根据键去查提高查询速度,值默认为0
			}
		}
		
		
		
		$start = strtotime($this->date) + 86400;			//第二天算起
		$seven = strtotime($this->date) + 6*86400;			//第七天	
		$end = strtotime($this->date) + 14*86400;			//第十五天
		
		$startdate = date('Y-m-d', $start);
		$sevendate = date('Y-m-d', $seven);		
		$enddate = date('Y-m-d', $end);						

		for($i = $start; $i <= $seven; $i = $i+86400) {
			$dateArr[] = date('Y-m-d', $i);
		}
		$dateArr[] = $enddate;	
		
		$dlist = $obj -> table("detail_login") -> where(array("left(d_date,10) >=" => $startdate, "left(d_date,10) <=" => $enddate )) ->group('d_user,left(d_date,10)') ->select();
		
		if($dlist != '') {
			foreach($dlist as $item) {
				$date = substr($item['d_date'], 0, 10);
				if($date <= $sevendate) {
					if(array_key_exists($item["d_user"], $names)){
						if(isset($result[$date]['people'])) {
							$result[$date]['people'] ++;
						} else {
							$result[$date]['people'] = 1;
						}
						
						if($date == $enddate) {					//保存第七天留存用户，用于计算第十五天留存率
							$seven_user[$item["d_user"]] = 0;
						}
					} 
				} else {
					$eight_fiften_user[$item["d_user"]] = 0;
				}
			}
			
			foreach($eight_fiften_user as $k => $val) {			//计算第十五天留存用户
				if(array_key_exists($k, $seven_user)) {
					if(isset($result[$enddate]['people'])) {
							$result[$enddate]['people'] ++;
					} else {
							$result[$enddate]['people'] = 1;
					}
				}
			}
			
			if(!empty($result)) {
				$curdate = array();		//保存当前有数据的日期
				foreach($result as $date => $item) {
					$result[$date]['percent'] = sprintf('%0.2f',($result[$date]['people']/$count)*100);
					$curdate[] = $date;
				}
				
				$diff = array_diff($dateArr, $curdate);
				foreach($diff as $e) {
					$result[$e]['people'] = 0;
					$result[$e]['percent'] = 0;
				}
			}
			
			ksort($result);
		}
		
		// $qtime = strtotime($this->date) + 86400;
		// for($i = $qtime; $i <= $qtime + 13*86400; $i = $i+86400){ 	//第二天之后的十四天数据	
			// $date = date("Y-m-d",$i);
			// $dlist = $obj->table("detail_login")->field("distinct d_user")->where(array("left(d_date,10)" => $date))->select();
			// $sum = 0;										//统计该天留存人数
			// if($dlist != ''){
				// foreach($dlist as $item){
					// if(in_array($item["d_user"],$names)){
						// $sum++;
					// }
				// }
			// }
			// $result[$date]['people'] = $sum;
			// $result[$date]['percent'] = sprintf('%0.2f',($sum/$count)*100);
		// }
		
		$union = array(
					"date"=>$this->date,
					"result"=>$result,
				);
		echo json_encode($union);
		exit;
	}
	
	/**
	 * 统计月活跃用户：当月有过登录的用户（去重复）
	 */
	public function getMonthStat(){
		$point = D("game".$this->ip);
		$list = array();		//保存1月到12月的月活跃用户数
		$year = substr($this->date, 0, 4);
		for($i = 1; $i <=12; $i++){
			if($i < 10){
				$month = '0'.$i;
			}
			$bo = $point -> table('detail_login') -> field('distinct d_userid') -> where('left(d_date,7) ="'.$year.'-'.$month.'"') -> select();
			//$bo = false;
			if($bo != false){
				$list[] = count($bo);
			}else{
				$list[] = 0;
			}
		}

		echo json_encode(array(
			'list' => $list
		));
		exit;
	}
	
	
	/**
	 * 获取活跃粘性信息
	 */
	public function getStick(){
		//暂时切掉！！
		
		$point = D('game'.$this->ip);
		$finshdate = get_var_value('finshdate');
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$ip = '';				//服务器
		$date = ''; 			//日期
		$xzyh = 0; 				//新增用户
		$rhyh = 0;				//日活跃用户
		$yhyy = 0;				//月活跃用户
		$dau_m = 0;				//DAU/MAU
		$lihy = 0;				//累计活跃用户
		$hyl = 0;				//活跃率
		
		if($this->ip != -1){
			list($ip) = autoConfig::getNameByIp($this->ip );
		}
		
		if($finshdate){			//开服时间
			$list = $point -> table('user') -> where('u_date <="'.$finshdate.'"') -> select();
			if(is_array($list)) {		
			}
					
		}else{					//选择区间
		
		}
		
	}
	
}