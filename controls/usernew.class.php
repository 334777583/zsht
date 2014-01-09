<?php
/**
 * FileName: usernew.class.php
 * Description:新进用户留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com,hjt
 * Date : 2013-9-24 18:20:49
 * Version:1.00
 */
class usernew{
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;

	
	/**
	*初始化数据
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
	*计算新进用户留存等级分布（新进用户N日留存用户：查询当天的新进登陆用户，在第N天仍有登陆行为的用户）
	*/
	public function getKeepLV() {
		$ip = get_var_value('ip');
		
		$point = D(GNAME.$ip);
		
		//查出最初开服时间
		$listdate = $point -> table('detail_login') 
						   -> field('d_date') 
						   -> order('d_date asc') 
						   -> limit(0,1)
						   -> find();
		$list_date = isset($listdate['d_date'])?date("Y-m-d",strtotime($listdate['d_date'])):date("Y-m-d",strtotime("-1 day"));//如果表里没数据 默认1天前
		$startdate = get_var_value("startdate") == NULL?$list_date:get_var_value("startdate");
		
		if($ip && $startdate) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			$count_arr = array();
			
			if($startdate < date("Y-m-d")) {
					
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
						$count_arr = array(
							'two_num'=>0,
							'two_per'=>0,
							'three_num'=>0,
							'three_per'=>0,
							'four_num'=>0,
							'four_per'=>0,
							'five_num'=>0,
							'five_per'=>0,
							'six_num'=>0,
							'six_per'=>0,
							'seven_num'=>0,
							'seven_per'=>0
							);
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
										$count_arr[$key."_per"]  = $count_arr[$key."_per"] + sprintf('%.2f', $item[$key]*100/$sum) ;
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
							$count_arr['two_num']  = $count_arr['two_num'] + $two_num ;
							$count_arr['three_num']  = $count_arr['three_num'] + $three_num ;
							$count_arr['four_num']  = $count_arr['four_num'] + $four_num ;
							$count_arr['five_num']  = $count_arr['five_num'] + $five_num ;
							$count_arr['six_num']  = $count_arr['six_num'] + $six_num ;
							$count_arr['seven_num']  = $count_arr['seven_num'] + $seven_num ;

						}
						
					}
				}
			}
			$filename = '';
			if(isset($list) && count($list) > 0){
				//封装数据
				//$result = $list;//输出数据
				$excel = $data;//excel 数据
				$excel_count = count($excel);
				
				$excel_array = array();
				for($i = 0 ;$i < $excel_count ; $i++){				
					$excel_array = $excel;
					if(($i % 1000) == 0){
						//写入缓存目录
						$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
						$handle = fopen($tmpfname, "w");
						fwrite($handle, json_encode($excel_array));
						$excel_array = $excel;
					}
				}
				$filename = base64_encode($tmpfname);
				fclose($handle);
			}
			$json = array(
				'result' => $data,
				'count'=>$count_arr,
				'filename' => $filename,
				'startDate' => $startdate
			);
			
			
			echo json_encode($json);
			exit;
		} else {
			echo '1';
		}
	}
	
	/**
	*计算新进用户流失等级分布（新进用户N日流失用户：查询当天的新进登陆用户，在第N天没有登陆行为的用户）
	*/
	public function getLoseLV() {
		$ip = get_var_value('ip');
		
		$point = D(GNAME.$ip);
		
		$listdate = $point -> table('detail_login') 
						   -> field('d_date') 
						   -> order('d_date asc') 
						   -> limit(0,1)
						   -> find();
		$list_date = isset($listdate['d_date'])?date("Y-m-d",strtotime($listdate['d_date'])):date("Y-m-d",strtotime("-1 day"));//如果表里没数据 默认1天前
		$startdate = get_var_value("startdate") == NULL?$list_date:get_var_value("startdate");
		
		if(true) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			//if($startdate < date("Y-m-d")) {					
			if($ip && $startdate) {		
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
			$filename = '';
			if(isset($list) && count($list) > 0){
				//封装数据
				//$result = $list;//输出数据
				$excel = $data;//excel 数据
				$excel_count = count($excel);
				
				$excel_array = array();
				for($i = 0 ;$i < $excel_count ; $i++){				
					$excel_array = $excel;
					if(($i % 1000) == 0){
						//写入缓存目录
						$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
						$handle = fopen($tmpfname, "w");
						fwrite($handle, json_encode($excel_array));
						$excel_array = $excel;
					}
				}
				$filename = base64_encode($tmpfname);
				fclose($handle);
			}
			$json = array(
				'result' => $data,
				'filename' =>$filename,
				'startDate' => $startdate
			);
			
			
			echo json_encode($json);
			exit;
		
		} else {
			echo '1';
		}
	}
	
	/**
	*计算新进用户流失任务id分布（新进用户N日流失用户：查询当天的新进登陆用户，在第N天没有登陆行为的用户）
	*/
	public function getTaskLV() {
		$ip = get_var_value('ip');
		
		$point = D(GNAME.$ip);
		
		$listdate = $point -> table('detail_login') 
						   -> field('d_date') 
						   -> order('d_date asc') 
						   -> limit(0,1)
						   -> find();
		$list_date = isset($listdate['d_date'])?date("Y-m-d",strtotime($listdate['d_date'])):date("Y-m-d",strtotime("-1 day"));//如果表里没数据 默认1天前
		$startdate = get_var_value("startdate") == NULL?$list_date:get_var_value("startdate");
		
		if($ip && $startdate) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			//if($startdate < date("Y-m-d")) {					
			if(true) {
			
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
			$filename = '';
			if(isset($list) && count($list) > 0){
				//封装数据
				//$result = $list;//输出数据
				$excel = $data;//excel 数据
				$excel_count = count($excel);
				
				$excel_array = array();
				for($i = 0 ;$i < $excel_count ; $i++){				
					$excel_array = $excel;
					if(($i % 1000) == 0){
						//写入缓存目录
						$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
						$handle = fopen($tmpfname, "w");
						fwrite($handle, json_encode($excel_array));
						$excel_array = $excel;
					}
				}
				$filename = base64_encode($tmpfname);
				fclose($handle);
			}
			
			$json = array(
				'result' => $data,
				'filename' => $filename,
				'startDate' => $startdate
			);
			
			
			echo json_encode($json);
			exit;
		
		} else {
			echo '1';
		}
	}
	
	/**
	 * 导出excel
	 */
	public function keepexcel(){
		$startdate = get_var_value('startdate');
		$f = base64_decode($_GET['f']);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		$list = json_decode(file_get_contents($f),true);
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
		require_once(AClass.'phpexcel/PHPExcel.php');
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '留存等级'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '新进用户次日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '新进用户三日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '新进用户四日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '新进用户五日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '新进用户六日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '新进用户七日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '百分比');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["level"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["two_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["two_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["three_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["three_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["four_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["four_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["five_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["five_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["six_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["six_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["seven_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["seven_per"]);
				}	
			}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "新进用户留存分析_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出excel
	 */
	public function loseexcel(){
		$startdate = get_var_value('startdate');
		$f = base64_decode($_GET['f']);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		$list = json_decode(file_get_contents($f),true);
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
		require_once(AClass.'phpexcel/PHPExcel.php');
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '留存等级'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '新进用户次日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '新进用户三日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '新进用户四日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '新进用户五日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '新进用户六日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '新进用户七日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '百分比');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["level"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["two_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["two_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["three_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["three_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["four_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["four_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["five_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["five_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["six_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["six_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["seven_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["seven_per"]);
				}	
			}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "新进用户流失分析_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出excel
	 */
	public function taskexcel(){
		$startdate = get_var_value('startdate');
		$f = base64_decode($_GET['f']);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		$list = json_decode(file_get_contents($f),true);
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
		require_once(AClass.'phpexcel/PHPExcel.php');
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
		$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '留存等级'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '新进用户次日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '新进用户三日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '新进用户四日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '新进用户五日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '新进用户六日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '百分比');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '新进用户七日留存总数');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '百分比');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["level"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["two_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["two_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["three_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["three_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["four_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["four_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["five_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["five_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["six_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["six_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["seven_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["seven_per"]);
				}	
			}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "新进用户任务分析_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
}