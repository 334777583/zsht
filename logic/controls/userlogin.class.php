<?php
/**
 * FileName: userlogin.class.php
 * Description: 登录概况
 * Author: xiaochengcheng,hjt
 * Date: 2013-9-24 18:20:29
 * Version: 1.02
 **/
class userlogin{
	/**
	 * 服务器IP
	 * @var String
	 */
	private $ip;
	
	/**
	 * 开始时间，默认当天的前七天
	 * @var String
	 */
	private $startDate;
	
	/**
	 * 结束时间，默认今天
	 * @var String
	 */
	private $endDate;
	
	/**
	 * 每页显示记录数
	 * @var int
	 */
	private $pageSize = 10;
	
	/**
	 * 当前页
	 * @var int
	 */
	private $curPage = 1;
	
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	/**
	 * 初始化数据
	 */
	
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00400200", $this->user["code"])){
				echo "not available";
				exit();
			}
		}
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->startDate = get_var_value("startDate") == NULL?date("Y-m-d",strtotime("-7 day")):date("Y-m-d",strtotime(get_var_value("startDate")));
		$this->endDate = get_var_value("endDate") == NULL?date("Y-m-d",strtotime("-1 day")):date("Y-m-d",strtotime(get_var_value("endDate")));
		$this->pageSize = get_var_value("pageSize") == NULL?10:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
	}
	
	/**
	 * FunctionName: getdata
	 * Description: ajax获取登录概况数据
	 * Author: hjt	
	 * Parameter：null
	 * Return: json 
	 * create: 2013-4-8 14:23:40
	 * update: 2013-9-6 15:26:24
	 */
	public function getdata(){
		$obj = D(GNAME.$this->ip);
		if($this->ip == -1){
			$this->ip = current($obj ->table("main_login")->field( 'distinct m_service')->order('m_service asc') -> find());
		}
		
		//查出最初开服时间
		$listdate = $obj-> table("main_login") -> field('m_date') -> order('m_date asc')-> limit(0,1) -> find();
		$list_date = isset($listdate['m_date'])?$listdate['m_date']:date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$this -> startDate = get_var_value("startDate") == NULL?$list_date:get_var_value("startDate");
		$list = $obj -> table("main_login") 
					 -> order('m_date desc') 
					 -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))
					 -> select();
		
		$chartList = array();	
		if($list == '') {			//没有记录时，默认为0
			$start = strtotime($this->startDate);
			$end = strtotime($this->endDate);
			$n = 0;					
			for($i = $start; $i <= $end; $i = $i+86400) {
				$chartList[$n]['m_date'] = date('Y-m-d', $i);
				$chartList[$n]['m_creat'] = 0;
				$chartList[$n]['m_login'] = 0;
				$chartList[$n]['m_sametime'] = 0;
				$chartList[$n]['m_maxsametime'] = 0;
				$n++;
			}
			
		}else {						
			$create_arr = array();				//计算创号数
			$create  = $obj-> table("createplay") ->select();
			if($create != '') {
				foreach($create as $item) {
					$create_arr[$item['c_date']] = $item['c_csuccess'];
				}
			}
			
			$dateArr = array();					//选择时间段
				
			$start = strtotime($this->startDate);
			$end = strtotime($this->endDate);

			for($i = $start; $i <= $end; $i = $i+86400) {
				$dateArr[] = date('Y-m-d', $i);
			}
			$login_temp = $obj -> table("login_temp") 
							   -> where("l_date >= '".$this->startDate."' and l_date <= '".$this->endDate."'") 
							   -> select();	//先从缓存表取
			
			$login_date = $obj -> table("login_temp") -> field("distinct l_date") -> select();//缓存表统计过的日期
			$login_result = array();
			$exist_date = array();						//统计过的日期，一维数组
			$login_field = array('2' => 'l_two','3' => 'l_three', '5' => 'l_five','10' => 'l_ten','15' => 'l_fifteen');
			if($login_temp != '') {						//组装数据，以日期为键值
				foreach($login_temp as $l) {
					foreach($login_field as $field) {
						$login_result[$l['l_date']][$field] = $l[$field];
					}
				}
				
				foreach($login_date as $da) {			//组装数据
					$exist_date[] = $da['l_date'];
				}
				
				$dateArr = array_diff($dateArr, $exist_date);	 //获取没有统计的日期
				
			}
			
			foreach($dateArr as $dates){
				$login_all = $obj -> table("online_sec") 
									-> field('count(*) as count_id,o_userid,left(o_date,10) as o_date')
									-> where("left(o_date,10) = '".$dates."'") 
									-> group('o_userid,left(o_date,10)') 
									-> select();
										
				if($login_all != ''){
					foreach($login_field as $k => $field){//计算2登等信息
						$login_result[$dates][$field] = 0;
						foreach($login_all as $o) {
							if($o['count_id'] >= $k){//判断登录数是否符合2登等信息
								$login_result[$dates][$field] ++;
							}
						}
					}
				}
			
				
				//获取所有登录信息，计算2登等信息（不是同一天有2次登录的算2登）
				//$user_info = array();	//保存指定日期前用户对应的登录次数
				
				/*if($login_all != '') {
					foreach($login_all as $o) {
						if(isset($user_info[$o['o_userid']])) {
							$user_info[$o['o_userid']] ++;
						}else {
							$user_info[$o['o_userid']] = 1;
						}
					}
				}
				
				if(!empty($user_info)) {
					foreach($user_info as $user) {
						foreach($login_field as $k => $field) {
							if($user >= $k) {
								if(isset($login_result[$dates][$field])) {
									$login_result[$dates][$field] ++; 
								} else {
									$login_result[$dates][$field] = 1;
								}
							}else {
								if(!isset($login_result[$dates][$field])) {
									$login_result[$dates][$field] = 0;
								}
							}		
						}
					}		
				}*/
			}
			
			if(!empty($login_result)) {			//插入缓存表
				foreach ($login_result as $k => $login) {
					if(in_array($k, $dateArr)) {
						$obj -> table("login_temp") -> insert(array(
							'l_date'  	 	=> $k,
							'l_two'	  	 	=> $login['l_two'],
							'l_three'		=> $login['l_three'],
							'l_five' 		=> $login['l_five'],
							'l_ten' 		=> $login['l_ten'],
							'l_fifteen' 	=> $login['l_fifteen'],
							'l_inserttime'  => date('Y-m-d H:i:s')
						));
					}
				}
			}

			foreach($list as $k => $item) {				//赋值到原来数组，并覆盖原来值
				if(isset($create_arr[$item['m_date']])) {
					$list[$k]['m_creat'] = $create_arr[$item['m_date']];
				}else {
					$list[$k]['m_creat'] = 0;
				}
				
				foreach ($login_field as $key => $val) {
					if(isset($login_result[$item['m_date']])) {
						$list[$k][$val] = $login_result[$item['m_date']][$val];
					}else {
						$list[$k][$val] = 0;
					}
				}
				if($item['m_maxtime'] > 86400){
					$list[$k]['m_maxtime'] = $item['m_maxtime']%86400;
				}
			}
			
			//显示图表
			$chartList = $obj -> table("main_login") 
					 -> field('m_date,m_creat,m_login,m_sametime,m_maxsametime')
					 -> order('m_date asc') 
					 -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))
					 -> select();
		}
		
		
		$total = $obj -> table("main_login")->where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax","go","page");
		$pageHtml = $page->getPageHtml();
		$comb = array(
					'list'=>$list,
					'chartList' => $chartList,
					'startDate'=>$this->startDate,
					'endDate'=>$this->endDate,
					'ip'=>$this->ip,
					'total'=>$total,
					'curPage'=>$this->curPage,
					'pageSize' =>$this->pageSize,
					'pageHtml'=>$pageHtml
				);
 		echo json_encode($comb);
		exit(); 
	}
	
	/**
	 * ajax获取统计数据(用于表格)
	 */
	public function getJsonData(){
		$obj = D(GNAME.$this->ip);
		if($this->ip == -1){
			$this->ip = current($obj -> table("main_login")->field( 'distinct m_service')->order('m_service asc') -> find());
		}
		
		$total = $obj->table("main_login")-> order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax");
		$pageHtml = $page->getPageHtml();
		$list = $obj-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->limit(intval($page->getOff()),intval($this->pageSize))->select();
		if($list != '') {
			$create_arr = array();	
			$create  = $obj-> table("createplay") ->select();
			if($create != '') {
				foreach($create as $item) {
					$create_arr[$item['c_date']] = $item['c_csuccess'];
				}
			}
			
			$login_temp = $obj -> table("login_temp") -> where("l_date >= '".$this->startDate."' and l_date <= '".$this->endDate."'") -> select();	//先从缓存表取
			$login_result = array();
			$login_field = array('2' => 'l_two','3' => 'l_three', '5' => 'l_five','10' => 'l_ten','15' => 'l_fifteen');
			if($login_temp != '') {						//组装数据，以日期为键值
				foreach($login_temp as $l) {
					foreach($login_field as $field) {
						$login_result[$l['l_date']][$field] = $l[$field];
					}
				}
				
				
			}
		
			foreach($list as $k => $item) {
				if(isset($create_arr[$item['m_date']])) {
					$list[$k]['m_creat'] = $create_arr[$item['m_date']];
				}else {
					$list[$k]['m_creat'] = 0;
				}
				
				foreach ($login_field as $key => $val) {
					if(isset($login_result[$item['m_date']])) {
						$list[$k][$val] = $login_result[$item['m_date']][$val];
					}else {
						$list[$k][$val] = 0;
					}
				}
			}
		}
		$oriList = $obj-> table("main_login")-> order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->select();
		if(is_array($oriList)){
			foreach($oriList as $k => $item) {
				if(isset($create_arr[$item['m_date']])) {
					$oriList[$k]['m_creat'] = $create_arr[$item['m_date']];
				}else {
					$oriList[$k]['m_creat'] = 0;
				}
				
				foreach ($login_field as $key => $val) {
					if(isset($login_result[$item['m_date']])) {
						$oriList[$k][$val] = $login_result[$item['m_date']][$val];
					}else {
						$oriList[$k][$val] = 0;
					}
				}
			}
		}
		$json = array(
					'list'=>$list,
					'oriList' => $oriList,
					'total'=>$total,
					'pageHtml'=>$pageHtml
				);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * ajax获取实时数据
	 */
	public function getCurData(){
		$oriArr = array();						//分页数据
		$minArr =  array();						//原始数据
		$com = array();							//json数据
		
		$interval = get_var_value('interval');	//时间间隔(1:每分钟；2：每5分钟；3：每1小时)
		$obj = D(GNAME.$this->ip);
		if($this->endDate != date("Y-m-d")){	//根据时间查询
			$total = $obj->table("history_num")->where(array("h_date like "=>$this->endDate."%"))->total();
			$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"digFormAjax","digGo","digPage");
			$pageHtml = $page->getPageHtml();
			$oriArr = $obj->table("history_num")->field("h_date,h_num")->where(array("h_date like "=>$this->endDate."%"))->order("h_date desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
			$minArr = $obj->table("history_num")->field("h_date,h_num")->where(array("h_date like "=>$this->endDate."%"))->select();
			$com = array(
						"olist"=>$oriArr,
						"minArr" => $minArr,
						"pageHtml"=>$pageHtml,
						"date"=>$this->endDate
					);
		}else{									//实时刷新
			/*
			$this->jsonUrl = dirname(__FILE__).DIRECTORY_SEPARATOR."json.txt";
			$data = file_get_contents($this->jsonUrl);
			*/
			$data = file_get_contents(JURL);
			$tempArr = json_decode($data,true);
			if(is_array($tempArr) && $interval) {	//根据不同的时间间隔显示数据
				switch($interval) {
					case 2 :						//每5分钟
						foreach($tempArr as $time => $num) {
							if(substr($time, 14, 2) % 5 != 0) {
								unset($tempArr[$time]);
							}
						}
						break;
					case 3 :						//每1小时
						foreach($tempArr as $time => $num) {
							if(substr($time, 14, 2) != '00') {
								unset($tempArr[$time]);
							}
						}
						break;
				}
			}
			
			$total = count($tempArr);
			$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"digFormAjax","digGo","digPage");
			$pageHtml = $page->getPageHtml();
			
			if(is_array($tempArr)){
				foreach($tempArr as $time=>$num){
					$minArr [] = array("h_date"=>$time,"h_num"=>$num);
				}
				
				if(!empty($minArr)) {		
					$sum = count($minArr)-1;
					$off = intval($page->getOff());
					$first = $sum-$off;
					$last = $sum-intval($page->getOff())-intval($this->pageSize);
					for($i = $first; $i > $last ; $i --) {
						if($i < 0) {
							break;
						}
						$oriArr[] = $minArr[$i];
					}
				}
				
			}
			
			$curCount = 0;		//当前在线
			if(isset($minArr[$total-1]["h_num"])){
				$curCount = $minArr[$total-1]["h_num"];
			}
			
			$com = array(
						"olist"=>$oriArr,
						"minArr" => $minArr,
						"pageHtml"=>$pageHtml,
						"date"=>$this->endDate,
						"curCount"=>$curCount
					);
		}
		echo json_encode($com);
		exit();
	}
	
	//用户每日平均在线时长
	public function getDaily(){
		$main = D(GNAME.$this->ip);
		
		//查出最初开服时间
		$listdate = $main-> table("main_login") -> field('m_date') -> order('m_date asc')-> limit(0,1) -> find();
		$list_date = isset($listdate['m_date'])?$listdate['m_date']:date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$this -> startDate = get_var_value("startDate") == NULL?$list_date:get_var_value("startDate");
		
		$list = $main-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->select();
		
		$chartList = array();
		if($list == '') {		//没有记录时默认为空
			$start = strtotime($this->startDate);
			$end = strtotime($this->endDate);
			
			$n = 0;					
			for($i = $start; $i <= $end; $i = $i+86400) {
				$chartList[$n]['m_date'] = date('Y-m-d', $i);
				$chartList[$n]['m_count'] = 0;
				$n++;
			}
		}
		
		$json = array(
			'list' => $list, 
			'chartList' => $chartList,
			'startDate'=>$this->startDate,
			'endDate'=>$this->endDate,
		);
		
		echo json_encode($json);
		exit();
	}
	
	
	//日平均在线时长分布
	public function getDuration(){
		$result = array();
		$result["005"] = $result["510"] = $result["1030"] = $result["3060"] = $result["12"] = $result["24"] = $result["48"] = $result["8m"] = 0;
		$online = D(GNAME.$this->ip);
		
		$Listdate = $online->table("online_sec") -> field('o_date') -> order('o_date asc') -> find();
		$list_date = isset($listdate['o_date'])?$listdate['m_date']:date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$this->startDate = get_var_value("startDate") == NULL?$list_date:get_var_value("startDate");
		
		if($this->startDate != $this->endDate){
			$endDate = date('Y-m-d', strtotime($this->endDate)+86400);
			$userList =  $online->table("online_sec")->where(array('o_date >='=>$this->startDate,"o_date <"=>$endDate))->order('o_date asc')->select();
		}else{ 			
			$userList =  $online->table("online_sec")->where(array('o_date like'=>$this->startDate."%"))->order('o_date asc')->select();
		}
		
		if(is_array($userList)) {
			$tem_arr = array();
			foreach($userList as $user){
				if(isset($tem_arr[$user['o_userid']]['seconds'])) {		//计算总时长
					$tem_arr[$user['o_userid']]['seconds'] +=  $user['o_second'];
				}else {
					$tem_arr[$user['o_userid']]['seconds'] = $user['o_second'];	
				}
					
				$date = substr($user['o_date'], 0, 10);
				if(!isset($tem_arr[$user['o_userid']]['date'])){
					$tem_arr[$user['o_userid']]['date'] = $date;
				}
				if(isset($tem_arr[$user['o_userid']]['days'])) {
					if($tem_arr[$user['o_userid']]['date'] != $date) {
						$tem_arr[$user['o_userid']]['days'] ++;
						$tem_arr[$user['o_userid']]['date'] = $date;
					}
				}else {
					$tem_arr[$user['o_userid']]['days'] = 1 ;
				}
			}
			
			if(!empty($tem_arr)) {
				foreach($tem_arr as $obj) {
					$sum = $obj['seconds'];
					$days = $obj['days'];
					if($days == 0) continue;
					$average = floor($sum/$days);
					if($average >= 0 && $average < 300){
						$result["005"] += 1;
					}else if($average >= 300 && $average < 600){
						$result["510"] += 1;
					}else if($average >= 600 && $average < 1800){
						$result["1030"] += 1;
					}else if($average >= 1800 && $average < 3600){
						$result["3060"] += 1;
					}else if($average >= 3600 && $average < 7200){
						$result["12"] += 1;
					}else if($average >= 7200 && $average < 14400){
						$result["24"] += 1;
					}else if($average >= 14400 && $average < 28800){
						$result["48"] += 1;
					}else if($average >= 28800){
						$result["8m"] += 1;
					}
				}
			}
			
		}
		echo json_encode($result);
		exit();
	}
	
	/**
	 * 导出excel
	 */
	public function writeExcel(){
		$obj = D(GNAME.$this->ip);
		if($this->ip == -1){
			$this->ip = current($obj ->table("main_login")->field( 'distinct m_service')->order('m_service asc') -> find());
		}
		$list = $obj-> table("main_login")->order('m_date asc') -> where(array('m_date >='=>$this->startDate,"m_date <="=>$this->endDate))->select();
		if($list != '') {
			$create_arr = array();	
			$create  = $obj-> table("createplay") ->select();
			if($create != '') {
				foreach($create as $item) {
					$create_arr[$item['c_date']] = $item['c_csuccess'];
				}
			}
			
			$login_temp = $obj -> table("login_temp") -> where("l_date >= '".$this->startDate."' and l_date <= '".$this->endDate."'") -> select();	//先从缓存表取
			$login_result = array();
			$login_field = array('2' => 'l_two','3' => 'l_three', '5' => 'l_five','10' => 'l_ten','15' => 'l_fifteen');
			if($login_temp != '') {						//组装数据，以日期为键值
				foreach($login_temp as $l) {
					foreach($login_field as $field) {
						$login_result[$l['l_date']][$field] = $l[$field];
					}
				}
				
			}
		
			foreach($list as $k => $item) {
				$list[$k]['m_count'] = $this->toformat($list[$k]['m_count']);
				$list[$k]['m_maxtime'] = $this->toformat($list[$k]['m_maxtime']);
				if(isset($create_arr[$item['m_date']])) {
					$list[$k]['m_creat'] = $create_arr[$item['m_date']];
				}else {
					$list[$k]['m_creat'] = 0;
				}
				
				foreach ($login_field as $key => $val) {
					if(isset($login_result[$item['m_date']])) {
						$list[$k][$val] = $login_result[$item['m_date']][$val];
					}else {
						$list[$k][$val] = 0;
					}
				}
			}
		}
	
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '创号数');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '登录数');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '登录总数');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '登录IP数');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '≥2登');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '≥3登');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '≥5登');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '≥10登');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '≥15登');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '平均在线时长');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', '最高在线时长');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', '平均同时在线人数');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', '最高同时在线人数');
		
		if (is_array($list)) {
			foreach($list as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.($k+2), $item["m_date"])
				->setCellValue('B'.($k+2), $item["m_creat"])
				->setCellValue('C'.($k+2), $item["m_login"])
				->setCellValue('D'.($k+2), $item["m_login_sum"])
				->setCellValue('E'.($k+2), $item["m_ip_num"])
				->setCellValue('F'.($k+2), $item["l_two"])
				->setCellValue('G'.($k+2), $item["l_three"])
				->setCellValue('H'.($k+2), $item["l_five"])
				->setCellValue('I'.($k+2), $item["l_ten"])
				->setCellValue('J'.($k+2), $item["l_fifteen"])
				->setCellValue('K'.($k+2), $item["m_count"])
				->setCellValue('L'.($k+2), $item["m_maxtime"])
				->setCellValue('M'.($k+2), $item["m_sametime"])
				->setCellValue('N'.($k+2), $item["m_maxsametime"]);
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

		$objPHPExcel->setActiveSheetIndex(0);

		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="登录概况.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
	
	/**
	 * 将单位为秒的时间转化成HH:mm:ss的格式
	 */
	private function toformat($s) {
		$hour = 0;
		$min = 0;
		$second = 0;
		$delimiter = ":";
		if ($s >= 3600) {      			//小时    
			$hour = floor($s/3600);
			$s = $s - 3600 * $hour;	
		} 
		if ($s >= 60 && $s < 3600) {  	//分钟	     
			$min = floor($s/60);
			$s = $s - 60 * $min;	
		}

		$second = $s;					//秒
		$format = "%1$02d".$delimiter."%2$02d".$delimiter."%3$02d";	
		return sprintf($format, $hour, $min, $second);                     
	}
}