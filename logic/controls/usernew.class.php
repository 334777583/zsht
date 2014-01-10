<?php
/**
 * FileName: usernew.class.php
 * Description:新进用户留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date : 2013-6-26 16:52:13
 * Version:1.00
 */
class usernew{
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;

	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400800', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 计算新进用户留存等级分布（新进用户N日留存用户：查询当天的新进登陆用户，在第N天仍有登陆行为的用户）
	 */
	public function getKeepLV() {
		$ip = get_var_value('ip');
		$startdate = get_var_value('startdate');

		if($ip && $startdate) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			if($startdate < date("Y-m-d")) {					
		
				$point = D('game'.$ip);
					
				$list = $point -> table('detail_login') -> where('left(d_date, 10) = "'.$startdate.'"') ->group('d_userid') -> select();	//该天新增角色
				
				if($list != '') {
					$sum = count($list);				//新增总数
					$new_ids = '(';
					foreach($list as  $item) {
						$new_ids .= '"'.$item['d_userid'] .'",';
					}

					$new_ids = rtrim($new_ids, ',');
					$new_ids .= ')'; 	

					$two_date = date('Y-m-d', (strtotime($startdate) + 86400));
					$three_date = date('Y-m-d', (strtotime($startdate) + 86400*2));	
					$four_date = date('Y-m-d', (strtotime($startdate) + 86400*3));	
					$five_date = date('Y-m-d', (strtotime($startdate) + 86400*4));	
					$six_date = date('Y-m-d', (strtotime($startdate) + 86400*5));	
					$seven_date = date('Y-m-d', (strtotime($startdate) + 86400*6));	
					
					$all = $point -> table('online_sec') -> fquery('select * from (select * from online_sec where left(o_date,10) > "' . $startdate  . '" and left(o_date,10) <= "'. $seven_date . '" and o_userid in ' . $new_ids .'order by o_date desc) as temp group by o_userid,left(o_date,10)');
					
					if($all != '') {
						foreach($all as $item) {
							$a_date = substr($item['o_date'], 0, 10);
							if($a_date == $two_date) {
								if(isset($result[$item['o_level']]['two'])) {
									$result[$item['o_level']]['two'] ++;
									
								} else {
									$result[$item['o_level']]['two'] = 1;
								}
							}else if($a_date == $three_date) {
								if(isset($result[$item['o_level']]['three'])) {
									$result[$item['o_level']]['three'] ++;
								} else {
									$result[$item['o_level']]['three'] = 1;
								}
							}else if($a_date == $four_date) {
								if(isset($result[$item['o_level']]['four'])) {
									$result[$item['o_level']]['four']++;
								} else {
									$result[$item['o_level']]['four'] = 1;
								}
							}else if($a_date == $five_date) {
								if(isset($result[$item['o_level']]['five'])) {
									$result[$item['o_level']]['five'] ++;
								} else {
									$result[$item['o_level']]['five'] = 1;
								}
							}else if($a_date == $six_date) {
								if(isset($result[$item['o_level']]['six'])) {
									$result[$item['o_level']]['six'] ++;
								} else {
									$result[$item['o_level']]['six'] = 1;
								}
							}else if($a_date == $seven_date) {
								if(isset($result[$item['o_level']]['seven'])) {
									$result[$item['o_level']]['seven'] ++;
								} else {
									$result[$item['o_level']]['seven'] = 1;
								}
							}
						
						}
					}
						
					if(!empty($result)) {			//组装数据
						ksort($result);
						$keys = array('two', 'three', 'four', 'five', 'six', 'seven');
						foreach($result as $level => $item) {
							$two_num = 0;
							$three_num = 0;
							$four_num = 0;
							$five_num = 0;
							$six_num = 0;
							$seven_num = 0;
							
							
							$two_per = '0%';
							$three_per = '0%';
							$four_per = '0%';
							$five_per = '0%';
							$six_per = '0%';
							$seven_per = '0%';
							
							
							foreach($keys as $key) {
								if(isset($item[$key])) {
									${$key."_num"} = $item[$key];
									
									if($sum > 0) {
										${$key."_per"} = sprintf('%.2f', $item[$key]*100/$sum) . "%";
									}
								}		
							}
						
							$data[] = array(
										'level' => $level,
										'two_num' => $two_num,
										'two_per' => $two_per,
										'three_num' => $three_num, 
										'three_per' => $three_per,
										'four_num' => $four_num, 
										'four_per' => $four_per,
										'five_num' => $five_num,
										'five_per' => $five_per,
										'six_num' => $six_num, 
										'six_per' => $six_per,
										'seven_num' => $seven_num, 										
										'seven_per' => $seven_per									
									);
						}
					}
				}
			}	
			echo json_encode(array('result' => $data));
			exit;
		} else {
			echo '1';
		}
	}
	
	/**
	 * 计算新进用户流失等级分布（新进用户N日流失用户：查询当天的新进登陆用户，在第N天没有登陆行为的用户）
	 */
	public function getLoseLV() {
		$ip = get_var_value('ip');
		$startdate = get_var_value('startdate');
		if($ip && $startdate) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			//if($startdate < date("Y-m-d")) {					
			if(true) {					
		
				$point = D('game'.$ip);
					
				$list = $point -> table('detail_login') -> where('left(d_date, 10) = "'.$startdate.'"') ->group('d_userid') -> select();	//该天新增角色
				
				if($list != '') {
					$sum = count($list);				//新增总数
					$keys = array('two', 'three', 'four', 'five', 'six', 'seven');
					
					$new_ids = array();					//新增角色集
					foreach($list as $item) {
						$new_ids[] = $item['d_userid'];
					}
																	
					$two_date = date('Y-m-d', (strtotime($startdate) + 86400));
					$three_date = date('Y-m-d', (strtotime($startdate) + 86400*2));	
					$four_date = date('Y-m-d', (strtotime($startdate) + 86400*3));	
					$five_date = date('Y-m-d', (strtotime($startdate) + 86400*4));	
					$six_date = date('Y-m-d', (strtotime($startdate) + 86400*5));	
					$seven_date = date('Y-m-d', (strtotime($startdate) + 86400*6));	
					
					$date_arr = array($two_date, $three_date, $four_date, $five_date, $six_date, $seven_date);

					$all = $point -> table('online_sec') -> fquery('select * from (select * from online_sec where left(o_date,10) >= "' . $startdate  . '" and left(o_date,10) <= "'. $seven_date . '" order by o_date desc) as temp group by o_userid,left(o_date,10)');
					
					if($all != '') {
						$daily = array();				//以日期为键值，保存对应的用户信息
						$user_by_id = array();			//以账号为键值，保存对应的用户信息
						foreach($all as $item) {
							$date_tmp = substr($item['o_date'], 0, 10);
							$daily[$date_tmp][$item['o_userid']] = $item;
							$user_by_id[$item['o_userid']][$date_tmp] = $item;
						}
						
						foreach($date_arr as $date) {
							foreach($keys as $key) {
								if($date == ${$key."_date"}) {			//计算n日流失(次日，三日等)
									//if($date == $six_date) {
										if(isset($daily[${$key."_date"}]) && is_array($daily[${$key."_date"}])) {
											$arr = array();				//n日登录用户（已去重）
											foreach($daily[${$key."_date"}]  as $id => $user) {
												$arr[] = $id;
											}
											$diff_id = array_diff($new_ids, $arr);				//流失角色ID
											
											foreach($diff_id as $id) {
												$level = 0;										//流失角色ID最高等级
												if(isset($user_by_id[$id]))	{
													foreach($user_by_id[$id] as $d => $user) {
														if($d < ${$key."_date"}) {				//计算用户最近一次的最高等级
															if($user['o_level'] > $level) {
																$level = $user['o_level'];
															}
														}
													}
												}
												if(isset($level) && $level !== 0) {
													if(isset($result[$level][$key])) {
														$result[$level][$key] ++;
														
													} else {
														$result[$level][$key] = 1;
													}
												}
											}
										}
									//}
								}
							}
						
						}
					}
					
						
					if(!empty($result)) {			//组装数据
						ksort($result);
						foreach($result as $level => $item) {
							$two_num = 0;
							$three_num = 0;
							$four_num = 0;
							$five_num = 0;
							$six_num = 0;
							$seven_num = 0;
							
							
							$two_per = '0%';
							$three_per = '0%';
							$four_per = '0%';
							$five_per = '0%';
							$six_per = '0%';
							$seven_per = '0%';
							
							
							foreach($keys as $key) {
								if(isset($item[$key])) {
									${$key."_num"} = $item[$key];
									
									if($sum > 0) {
										${$key."_per"} = sprintf('%.2f', $item[$key]*100/$sum) . "%";
									}
								}		
							}
						
							$data[] = array(
										'level' => $level,
										'two_num' => $two_num,
										'two_per' => $two_per,
										'three_num' => $three_num, 
										'three_per' => $three_per,
										'four_num' => $four_num, 
										'four_per' => $four_per,
										'five_num' => $five_num,
										'five_per' => $five_per,
										'six_num' => $six_num, 
										'six_per' => $six_per,
										'seven_num' => $seven_num, 										
										'seven_per' => $seven_per									
									);
						}
					}
				}
			}	
			echo json_encode(array('result' => $data));
			exit;
		
		} else {
			echo '1';
		}
	}
	
	/**
	 * 计算新进用户流失任务id分布（新进用户N日流失用户：查询当天的新进登陆用户，在第N天没有登陆行为的用户）
	 */
	public function getTaskLV() {
		$ip = get_var_value('ip');
		$startdate = get_var_value('startdate');
		if($ip && $startdate) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			//if($startdate < date("Y-m-d")) {					
			if(true) {					
		
				$point = D('game'.$ip);
					
				$list = $point -> table('detail_login') -> where('left(d_date, 10) = "'.$startdate.'"') ->group('d_userid') -> select();	//该天新增角色
				
				if($list != '') {
					$sum = count($list);				//新增总数
					$keys = array('two', 'three', 'four', 'five', 'six', 'seven');
					
					$new_ids = array();					//新增角色集
					foreach($list as $item) {
						$new_ids[] = $item['d_userid'];
					}
																	
					$two_date = date('Y-m-d', (strtotime($startdate) + 86400));
					$three_date = date('Y-m-d', (strtotime($startdate) + 86400*2));	
					$four_date = date('Y-m-d', (strtotime($startdate) + 86400*3));	
					$five_date = date('Y-m-d', (strtotime($startdate) + 86400*4));	
					$six_date = date('Y-m-d', (strtotime($startdate) + 86400*5));	
					$seven_date = date('Y-m-d', (strtotime($startdate) + 86400*6));	
					
					$date_arr = array($two_date, $three_date, $four_date, $five_date, $six_date, $seven_date);

					$all = $point -> table('online_sec') -> fquery('select * from (select * from online_sec where left(o_date,10) >= "' . $startdate  . '" and left(o_date,10) <= "'. $seven_date . '" order by o_date desc) as temp group by o_userid,left(o_date,10)');
					
					if($all != '') {
						$daily = array();				//以日期为键值，保存对应的用户信息
						$user_by_id = array();			//以账号为键值，保存对应的用户信息
						foreach($all as $item) {
							$date_tmp = substr($item['o_date'], 0, 10);
							$daily[$date_tmp][$item['o_userid']] = $item;
							$user_by_id[$item['o_userid']][$date_tmp] = $item;
						}
						
						foreach($date_arr as $date) {
							foreach($keys as $key) {
								if($date == ${$key."_date"}) {			//计算n日流失(次日，三日等)
									//if($date == $three_date) {
										if(isset($daily[${$key."_date"}]) && is_array($daily[${$key."_date"}])) {
											$arr = array();				//n日登录用户（已去重）
											foreach($daily[${$key."_date"}]  as $id => $user) {
												$arr[] = $id;
											}
											$diff_id = array_diff($new_ids, $arr);				//流失角色ID

											foreach($diff_id as $id) {
												$task = -1;										//流失角色任务id
												if(isset($user_by_id[$id]))	{
													$date_tmp = 0;
													foreach($user_by_id[$id] as $d => $user) {
														if($d < ${$key."_date"}) {				//计算用户最近一次的最高等级
															if($d > $date_tmp) {
																$task = $user['o_task'];
																$date_tmp = $d;
															}
														}
													}
												}
												if(isset($task) && $task !== -1) {
													if(isset($result[$task][$key])) {
														$result[$task][$key] ++;
														
													} else {
														$result[$task][$key] = 1;
													}
												}
											}
										}
									//}
								}
							}
						
						}
					}
					
						
					if(!empty($result)) {			//组装数据
						ksort($result);
						foreach($result as $level => $item) {
							$two_num = 0;
							$three_num = 0;
							$four_num = 0;
							$five_num = 0;
							$six_num = 0;
							$seven_num = 0;
							
							
							$two_per = '0%';
							$three_per = '0%';
							$four_per = '0%';
							$five_per = '0%';
							$six_per = '0%';
							$seven_per = '0%';
							
							
							foreach($keys as $key) {
								if(isset($item[$key])) {
									${$key."_num"} = $item[$key];
									
									if($sum > 0) {
										${$key."_per"} = sprintf('%.2f', $item[$key]*100/$sum) . "%";
									}
								}		
							}
						
							$data[] = array(
										'level' => $level,
										'two_num' => $two_num,
										'two_per' => $two_per,
										'three_num' => $three_num, 
										'three_per' => $three_per,
										'four_num' => $four_num, 
										'four_per' => $four_per,
										'five_num' => $five_num,
										'five_per' => $five_per,
										'six_num' => $six_num, 
										'six_per' => $six_per,
										'seven_num' => $seven_num, 										
										'seven_per' => $seven_per									
									);
						}
					}
				}
			}	
			echo json_encode(array('result' => $data));
			exit;
		
		} else {
			echo '1';
		}
	}
	
}