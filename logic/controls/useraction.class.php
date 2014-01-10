<?php
/**
 * FileName: useraction.class.php
 * Description:行为分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-22 10:29:52
 * Version:1.00
 */
class useraction{
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
			if(!in_array('00401100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->startdate = get_var_value("startdate") == NULL?date("Y-m-d",strtotime("-7 day")):date("Y-m-d",strtotime(get_var_value("startdate")));
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
	
	}
	
	/**
	 * 获取行为分析数据
	 */
	public function getAction() {
		$point = D('game'.$this->ip);
		
		$dateArr = array();					//选择时间段
		$result = array();					//行为分析结果
		
		$start = strtotime($this->startdate);
		$end = strtotime($this->enddate);

		for($i = $start; $i <= $end; $i = $i+86400) {
			$dateArr[] = date('Y-m-d', $i);
		}
		
		$action = $point -> table('action_temp') 
						 -> where('date >= "'.$this->startdate.'" and date <="'.$this->enddate.'"') 
						 -> select();		//先从数据库缓存表里面取（aciton_temp）
						 
		if($action != '') {
			foreach($action as $a) {
				$result[$a['date']] = $a;
			}
		}	
			
		
		foreach($dateArr as $k => $date) {
			if(isset($result[$date])) {
				continue;
			}
			
			$sum = 1;						//该天登陆角色数
			$cwltg = 0;						//闯皇陵通关
			$cwlcz = 0;						//闯皇陵重置
			$jqtg = 0;						//剧情通关
			$jqzj = 0;						//剧情增加
			$dgtg = 0;						//独孤求败通关
			$dgzj = 0;						//独孤求败增加
			$qm = 0;						//奇门
			$dj = 0;						//遁甲
			$ht = 0;						//河图
			$ls = 0;						//洛书
			$xl = 0;						//套装洗练
			$qy = 0;						//奇遇
			$du = 0;						//毒
			$jm = 0;						//经脉
			$sjwc = 0;						//随机日常完成
			$sjgm = 0;						//随机日常购买
			$cfwc = 0;						//重复日常完成
			$cfgm = 0;						//重复日常购买
			$sh = 0;						//收获
			$td = 0;						//推倒
			$py = 0;						//培养
			$sx = 0;						//刷新	
			
			
			$detail = $point -> table('detail_login') -> where('d_date like "'.$date.'%"') -> group('d_userid') -> select();
			if($detail != '') {
				$sum = count($detail);
			}else {
				continue;
			}
			
			$cwltg = $point -> table('dwltg') -> where('d_date like "' . $date . '%"') -> total();								//闯皇陵每人每天平均通关次数
			$cwlcz = $point -> table('dwlcz') -> where('d_date like "' . $date . '%"') -> total();								//闯皇陵每人每天平均重置次数
			$jqtg = $point -> table('fbtz') -> where('f_date like "' . $date . '%" and f_fblx = "剧情"') -> total();			//剧情副本每人每天通关次数
			$jqzj = $point -> table('fbgm') -> where('f_date like "' . $date . '%"') -> total();								//剧情副本每人每天平均增加次数
			$dgtg = $point -> table('fbtz') -> where('f_date like "' . $date . '%" and f_fblx = "独孤求败"') -> total();		//独孤求败每人每天通关次数
			$dgzj = $point -> table('fbcz') -> where('f_date like "' . $date . '%"') -> total();								//独孤求败每人每天平均增加次数
			$qm = $point -> table('zbzj') -> where('z_date like "' . $date . '%" and z_zbdj = 1') -> total();					//奇门每人每天洗练次数
			$dj = $point -> table('zbzj') -> where('z_date like "' . $date . '%" and z_zbdj = 2') -> total();					//遁甲每人每天洗练次数
			$ht = $point -> table('zbzj') -> where('z_date like "' . $date . '%" and z_zbdj = 3') -> total();					//河图每人每天洗练次数
			$ls = $point -> table('zbzj') -> where('z_date like "' . $date . '%" and z_zbdj = 4') -> total();					//洛书每人每天洗练次数
			$xl = $point -> table('zbjnxl') -> where('z_date like "' . $date . '%"') -> total();								//套装技能每人每天洗练次数
			$qy = $point -> table('qy') -> where('q_date like "' . $date . '%"') -> total();									//每人每天奇遇次数
			$du = $point -> table('sx') -> where('s_date like "' . $date . '%"') -> total();									//每人每天上香次数
			$jm = $point -> table('cm') -> where('c_date like "' . $date . '%"') -> total();									//每人每天冲脉次数
			$sjwc = $point -> table('wcrw') -> where('w_date like "' . $date . '%" and w_rw = "RANDOM"') -> total();			//每天每人完成随机日常的个数
			$sjgm  = $point -> table('gmrwcs') -> where('g_date like "' . $date . '%" and g_rw = "RANDOM"') -> total();			//每人每天随机日常购买次数
			$cfwc = $point -> table('wcrw') -> where('w_date like "' . $date . '%" and w_rw = "REPEAT"') -> total();			//每天每人完成重复日常的个数
			$cfgm  = $point -> table('gmrwcs') -> where('g_date like "' . $date . '%" and g_rw = "REPEAT"') -> total();			//每人每天重复日常购买次数
			$sh  = $point -> table('mvsh') -> where('m_date like "' . $date . '%"') -> total();									//天上人间每人每天收获次数
			$td  = $point -> table('mvtd') -> where('m_date like "' . $date . '%"') -> total();									//天上人间每人每天推倒次数
			$py  = $point -> table('mvpy') -> where('m_date like "' . $date . '%"') -> total();									//天上人间每人每天培养次数
			$sx  = $point -> table('mvdjsx') -> where('m_date like "' . $date . '%"') -> total();								//天上人间每人每天刷新次数

			$result[$date]['date'] = $date;
			$result[$date]['cwltg'] = ceil($cwltg/$sum); 
			$result[$date]['cwlcz'] = ceil($cwlcz/$sum); 
			$result[$date]['jqtg'] = ceil($jqtg/$sum); 
			$result[$date]['jqzj'] = ceil($jqzj/$sum); 
			$result[$date]['dgtg'] = ceil($dgtg/$sum); 
			$result[$date]['dgzj'] = ceil($dgzj/$sum); 
			$result[$date]['qm'] = ceil($qm/$sum); 
			$result[$date]['dj'] = ceil($dj/$sum); 
			$result[$date]['ht'] = ceil($ht/$sum); 
			$result[$date]['ls'] = ceil($ls/$sum); 
			$result[$date]['xl'] = ceil($xl/$sum); 
			$result[$date]['qy'] = ceil($qy/$sum); 
			$result[$date]['du'] = ceil($du/$sum); 
			$result[$date]['jm'] = ceil($jm/$sum); 
			$result[$date]['sjwc'] = ceil($sjwc/$sum); 
			$result[$date]['sjgm'] = ceil($sjgm/$sum); 
			$result[$date]['cfwc'] = ceil($cfwc/$sum); 
			$result[$date]['cfgm'] = ceil($cfgm/$sum); 
			$result[$date]['sh'] = ceil($sh/$sum); 
			$result[$date]['td'] = ceil($td/$sum); 
			$result[$date]['py'] = ceil($py/$sum); 
			$result[$date]['sx'] = ceil($sx/$sum); 
			
			if(strtotime($date) < strtotime(date('Y-m-d'))) {		//小于今天的数据保存到缓存表中
				$point -> table('action_temp') 
					   -> insert(array(
							  'date'	=> 	$date,
							  'cwltg'	=> 	$result[$date]['cwltg'],
							  'cwlcz'	=> 	$result[$date]['cwlcz'],
							  'jqtg'	=> 	$result[$date]['jqtg'],
							  'jqzj'	=> 	$result[$date]['jqzj'],	
							  'dgtg'	=> 	$result[$date]['dgtg'],	
							  'dgzj'	=> 	$result[$date]['dgzj'],
							  'qm'		=>	$result[$date]['qm'],
							  'dj'		=>	$result[$date]['dj'],
							  'ht'		=>	$result[$date]['ht'],
							  'ls'		=>	$result[$date]['ls'],
							  'xl'		=>	$result[$date]['xl'],
							  'qy'		=>	$result[$date]['qy'],
							  'du'		=>	$result[$date]['du'],
							  'jm'		=>	$result[$date]['jm'],
							  'sjwc'	=>	$result[$date]['sjwc'],
							  'sjgm'	=>	$result[$date]['sjgm'],
							  'cfwc'	=>	$result[$date]['cfwc'],
							  'cfgm'	=>	$result[$date]['cfgm'],
							  'sh'		=>	$result[$date]['sh'],
							  'td'		=>	$result[$date]['td'],
							  'py'		=>	$result[$date]['py'],
							  'sx'		=>	$result[$date]['sx'],
						)); 
			}
		}
		
		ksort($result);						//按照键名排序
	
		echo json_encode(array('result' => $result,'total' => count($result)));
		exit;
	}
	
	/**
	 * 删除指定日期前的表数据
	**/
	public function delete() {
		if(!in_array('00100401', $this->user['code'])){
			echo 'not available!';
			exit();
		}
		$ip = get_var_value('ip');
		if($ip) {
			$point = D('game'.$ip);
			
			$list  = $point -> field('date') -> table('action_temp') -> select();		//先取出已经做缓存的日期，清空它们的原始数据		
			if($list != '') {
				$date = '(';
				foreach($list as $item) {
					$date .= "'".$item['date'] . "',";
				}
				$date = rtrim($date, ',');
				$date .= ')';
				
				$cwltg = $point -> table('dwltg') -> where('left(d_date,10) in ' . $date) -> delete();						//闯皇陵每人每天平均通关次数
				$cwlcz = $point -> table('dwlcz') -> where('left(d_date,10) in ' . $date) -> delete();						//闯皇陵每人每天平均重置次数
				$jqtg = $point -> table('fbtz') -> where('left(f_date,10) in ' . $date ) -> delete();						//剧情副本每人每天通关次数
				$jqzj = $point -> table('fbgm') -> where('left(f_date,10) in ' . $date) -> delete();						//剧情副本每人每天平均增加次数
				$dgtg = $point -> table('fbtz') -> where('left(f_date,10) in ' . $date) -> delete();						//独孤求败每人每天通关次数
				$dgzj = $point -> table('fbcz') -> where('left(f_date,10) in ' . $date) -> delete();						//独孤求败每人每天平均增加次数
				$qm = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();							//奇门每人每天洗练次数
				$dj = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();							//遁甲每人每天洗练次数
				$ht = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();							//河图每人每天洗练次数
				$ls = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();							//洛书每人每天洗练次数
				$xl = $point -> table('zbjnxl') -> where('left(z_date,10) in ' . $date) -> delete();						//套装技能每人每天洗练次数
				$qy = $point -> table('qy') -> where('left(q_date,10) in ' . $date) -> delete();							//每人每天奇遇次数
				$du = $point -> table('sx') -> where('left(s_date,10) in ' . $date) -> delete();							//每人每天上香次数
				$jm = $point -> table('cm') -> where('left(c_date,10) in ' . $date) -> delete();							//每人每天冲脉次数
				$sjwc = $point -> table('wcrw') -> where('left(w_date,10) in ' . $date) -> delete();						//每天每人完成随机日常的个数
				$sjgm  = $point -> table('gmrwcs') -> where('left(g_date,10) in ' . $date) -> delete();						//每人每天随机日常购买次数
				$cfwc = $point -> table('wcrw') -> where('left(w_date,10) in ' . $date) -> delete();						//每天每人完成重复日常的个数
				$cfgm  = $point -> table('gmrwcs') -> where('left(g_date,10) in ' . $date) -> delete();						//每人每天重复日常购买次数
				$sh  = $point -> table('mvsh') -> where('left(m_date,10) in ' . $date) -> delete();							//天上人间每人每天收获次数
				$td  = $point -> table('mvtd') -> where('left(m_date,10) in ' . $date) -> delete();							//天上人间每人每天推倒次数
				$py  = $point -> table('mvpy') -> where('left(m_date,10) in ' . $date) -> delete();							//天上人间每人每天培养次数
				$sx  = $point -> table('mvdjsx') -> where('left(m_date,10) in ' . $date) -> delete();	
			}
			echo json_encode("success");
		} else {
			echo json_encode("error");
		}
	}
	
}