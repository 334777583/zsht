<?php
/**
 * FileName: userkeep.class.php
 * Description:留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-18 14:20:29
 * Version:1.00
 */
class userkeep{
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
	 * 时间
	 * @var string
	 */
	private $enddate;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
	
	}
	
	
	/**
	 * user_temp缓存表取数据
	 */
	private function getRecordByTemp(&$loseIds, &$keepIds, $date, $point) {
		$point -> table('user_temp') -> where('u_expire < "'.date("Y-m-d").'"') -> delete();	//检查过期的，先清除(默认三天后)
	
		$keep_temp = $point -> table('user_temp') -> where('u_type = 0 and u_date = "'.$date.'"') -> select();
		$lose_temp = $point -> table('user_temp') -> where('u_type = 1 and u_date = "'.$date.'"') -> select();
		if($keep_temp != '') {
			foreach($keep_temp as $keep) {
				$keepIds[] = array('o_id' => $keep['u_oid'],'userid' => $keep['u_userid']);
			}	
		}
		if($lose_temp != '') {
			foreach($lose_temp as $lose) {
				$loseIds[] = array('o_id' => $lose['u_oid'],'userid' => $lose['u_userid']);
			}	
		}
	}
	
	/**
	 * 留存等级分布
	 */
	public function getLD() {
		$point = D(GNAME.$this->ip);
		
		$loseIds = array();					//流失用户id
		$keepIds = array();					//留存用户id
		
		$this->getRecordByTemp($loseIds, $keepIds, $this->enddate, $point); //先从缓存表取数据
		$listdate = $point -> table('online_sec') -> field('o_date') -> order('o_id asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['o_date'])?date('Y-m-d',strtotime($listdate['o_date'])):date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$this->enddate = get_var_value("enddate") == NULL?date("Y-m-d",strtotime($list_date)+7*24*3600):date("Y-m-d",strtotime(get_var_value("enddate")));
		if(empty($loseIds) && empty($keepIds)) {							//缓存表不存在则重新计算
			//$bEight = date("Y-m-d",strtotime($this->enddate)-86400*7);
			$bEight = date("Y-m-d",strtotime('-6 day'.$this->enddate));//选择日期前八天到所选日期7天内没有登录过的为流失用户,反之为留存用户
			//$bSeven = date("Y-m-d",strtotime($this->enddate)-86400*6);
			$bSeven = date("Y-m-d",strtotime('-5 day'.$this->enddate));
			$btoday = date('Y-m-d',strtotime('+1 day'.$this->enddate));
			
			//八天前那天的登录用户
			$users = $point -> table('online_sec') -> fquery("SELECT o_id,o_userid FROM (SELECT * FROM online_sec where left(o_date,10) = '{$bEight}' ORDER BY o_date desc) as temp GROUP BY o_userid");	
						
			//往后七天的登录用户
			$seven_user = $point -> table('online_sec') -> fquery("SELECT o_id,o_userid FROM (SELECT * FROM online_sec where o_date >= '{$bSeven}' and o_date < '{$btoday}' ORDER BY o_date desc) as temp GROUP BY o_userid");
			
			if (is_array($users)) {
				$users_str = '';//八天前登录用户（用于二维降一维）
				$o_id_str = '';//八天前登录用户自增id（用于二维降一维）
				$seven_str = '';//往后七天的登录用户（用于二维降一维）
				$sev_id_str = '';//往后七天的登录用户自增id（用于二维降一维）
				
				foreach($users as $user){//将八天前登录用户数据 写成字符串
					$users_str .= $user['o_userid'].',';
					$o_id_str .= $user['o_id'].',';
				}
				$users_str = rtrim($users_str,',');
				$o_id_str = rtrim($o_id_str,',');
				
				foreach($seven_user as $seven){//将往后七天的登录用户数据 写成字符串
					$seven_str .= $seven['o_userid'].',';
					$sev_id_str .= $seven['o_id'].',';
				}
				$seven_str = rtrim($seven_str,',');
				$sev_id_str = rtrim($sev_id_str,',');
				
				$users_arr = explode(',',$users_str);//八天前登录用户整理成一维数组
				$o_id_arr = explode(',',$o_id_str);//八天前登录用户整理成一维数组
				$seven_arr = explode(',',$seven_str);//往后七天的登录用户整理成一维数组
				$sev_id_arr = explode(',',$sev_id_str);//往后七天的登录用户整理成一维数组
				
				foreach($seven_arr as $key => $val){//整理 往后七天的登录用户数据
					$seven_all[$val] = $val;
					$sev_id_all[$val] = $sev_id_arr[$key];
				}
				
				foreach($users_arr as $key => $val){//计算出留存用户
					if(isset($seven_all[$val])){
						$keepIds[] = array('o_id' => $sev_id_all[$val],'userid' => $val);
					}
				}
				
				$lose_arr = array_diff($users_arr,$seven_arr);//计算出流失用户
				foreach($lose_arr as $key => $val){
					$loseIds[] = array('o_id' => $o_id_arr[$key],'userid' => $val);
				}
				
				/*foreach ($users as $user) {			//计算流失用户和留存用户
					$flag = 1;						//标记该用户是否流失用户（1：是，0：不是）
					foreach($seven_user as $seven) {
						if( $user['o_userid'] ==  $seven['o_userid']) {
							 $keepIds[] = array('o_id' => $seven['o_id'],'userid' => $seven['o_userid']);
							 $flag = 0;
							 break;
						}
					}
					if($flag) {
						$loseIds[] = array('o_id' => $user['o_id'],'userid' => $user['o_userid']);
					}
				}*/
				
				if($this->enddate < date("Y-m-d")) {	//历史数据才进缓存表
					$k_insert_sql = '';					
					$l_insert_sql = '';
					$expire = date("Y-m-d", strtotime("+2 day"));
					foreach($keepIds as $keep) {
						$k_insert_sql .= "('" . $this->enddate . "','" . $keep['o_id'] . "','" . $keep['userid'] . "',". "0" . ",'" . $expire .  "'),"; 
					}
					
					foreach($loseIds as $lose) {
						$l_insert_sql .= "('" . $this->enddate . "','" . $lose['o_id'] . "','" . $lose['userid']  . "',". "1" . ",'" . $expire .  "'),"; 
						//break;
					}
					
					if($k_insert_sql !== '') {
						$k_insert_sql = rtrim($k_insert_sql, ',');
						$k_insert_sql .= ';';
						$sql = 'insert into user_temp(u_date,u_oid,u_userid,u_type,u_expire) values ' . $k_insert_sql;
						@$point -> table('user_temp') -> fquery($sql);
						unset($k_insert_sql);
						unset($sql);
					}
					
					if($l_insert_sql !== '') {
						$l_insert_sql = rtrim($l_insert_sql, ',');
						$l_insert_sql .= ';';
						$sql = 'insert into user_temp(u_date,u_oid,u_userid,u_type,u_expire) values ' . $l_insert_sql;
						@$point -> table('user_temp') -> fquery($sql);
						unset($l_insert_sql);
						unset($sql);
					}	
				}
			}
		}
	
		$result = array();				//留存等级（1~120级）
		$sum = 0;						//留存总人数
		
		for( $i = 1; $i<121; $i++ ) {	//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}
		
		if(!empty($keepIds)){
			$idSql = '';
			foreach($keepIds as $item) {
				$idSql .= $item['o_id'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$ld = $point -> table('online_sec') -> field('o_level, o_userid') -> where('o_id in ('.$idSql.')') ->select();   // 留存等级
			
			if	(is_array($ld)) {
				foreach ($ld as $item) {
					if(isset($result[$item['o_level']]['num'])) {
						$result[$item['o_level']]['num'] += 1;
						$sum++;
					}
				}
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
	
		echo json_encode(array(
			'result' => $result,
			'loseIds' => $loseIds,
			'keepIds' => $keepIds,
			'enddate' => $this->enddate
		));
		exit;
	
	}
	
	
	
	/**
	 *	留存用户装备强化等级分布（武器，头部，衣服，披风，项链，护腕，戒指，鞋子）
	 */
	public function getTBQH() {
		$point = D(GNAME.$this->ip);
		
		$keepIds = get_var_value('keepIds');	//留存用户id
		$part = get_var_value('part');			//部位
		
		$result = array();  					
		$sum = 0;								//总人数
		
		for ( $i = 0; $i<=16; $i++ ) {			//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}
		
		if ($keepIds && $part) {					//计算留存用户装备强化等级分布
			$idSql = '';
			foreach($keepIds as $item) {
				$idSql .= $item['userid'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$dataArr = $point -> table('zbqh') -> fquery("select * from (select * from zbqh where z_playid in ({$idSql}) and z_bw = '{$part}' ORDER BY z_date desc) as temp  GROUP BY z_playid,z_bw");
			
			if (is_array($dataArr)) {
				foreach ($dataArr as $data) {
					if(isset($result[$data['z_zbdj']]['num'])) {
						$result[$data['z_zbdj']]['num'] += 1;
						$sum ++;
					}
				}
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
		
		echo json_encode(array(
				'result' => $result
			));
		exit;
	}
	
	
	/**	
	 *	留存用户装备追加等级分布（武器，头部，衣服，披风，项链，护腕，戒指，鞋子）
	 */
	public function getZBZJ() {
		$point = D(GNAME.$this->ip);
		
		$keepIds = get_var_value('keepIds');	//留存用户id
		$part = get_var_value('part');			//部位
		
		$result = array();  					
		$sum = 0;								//总人数
		
		for ( $i = 0; $i<=16; $i++ ) {			//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}
		
		if ($keepIds && $part) {					//计算留存用户装备强化等级分布
			$idSql = '';
			foreach($keepIds as $item) {
				$idSql .= $item['userid'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$dataArr = $point -> table('zbzj') -> fquery("select * from (select * from zbzj where z_playid in ({$idSql}) and z_bw = '{$part}' ORDER BY z_date desc) as temp  GROUP BY z_playid,z_bw");
			
			if (is_array($dataArr)) {
				foreach ($dataArr as $data) {
					if(isset($result[$data['z_zbdj']]['num'])) {
						$result[$data['z_zbdj']]['num'] += 1;
						$sum ++;
					}
				}
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
		
		echo json_encode(array(
				'result' => $result
			));
		exit;
	
	}
	
	/**
	 *	留存用户套装等级分布（武器，头部，衣服，披风，项链，护腕，戒指，鞋子）
	 */
	public function getTZDJ() {
		$point = D(GNAME.$this->ip);
		
		$keepIds = get_var_value('keepIds');	//留存用户id
		$part = get_var_value('part');			//部位
		
		$result = array();  					
		$sum = 0;								//总人数
		
		for ( $i = 0; $i<=16; $i++ ) {			//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}
		
		if ($keepIds && $part) {				//计算留存用户装备强化等级分布
			$idSql = '';
			foreach($keepIds as $item) {
				$idSql .= $item['userid'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$dataArr = $point -> table('zbhc') -> fquery("select * from (select * from zbhc where z_playid in ({$idSql}) and z_bw = '{$part}'  ORDER BY z_tzdj desc) as temp GROUP BY z_playid,z_bw");
			
			if (is_array($dataArr)) {
				foreach ($dataArr as $data) {
					if(isset($result[$data['z_tzdj']]['num'])) {
						$result[$data['z_tzdj']]['num'] += 1;
						$sum ++;
					}
				}
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
		
		echo json_encode(array(
				'result' => $result
			));
		exit;
	
	}
	
	
	/**
	 *	留存用户套装技能个数分布
	 */
	public function getTZJN() {
		$point = D(GNAME.$this->ip);
		
		$keepIds = get_var_value('keepIds');	//留存用户id
		
		$result = array();  					
		$sum = 0;								//总人数
		
		for ( $i = 0; $i<=16; $i++ ) {			//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}
		
		if ($keepIds) {				//计算留存用户装备强化等级分布
			$idSql = '';
			foreach($keepIds as $item) {
				$idSql .= $item['userid'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$dataArr = $point -> table('zbhc') -> fquery("select * from (select * from zbhc where z_playid in ({$idSql}) ORDER BY z_zbjn desc) as temp  GROUP BY z_playid,z_bw");
			
			if (is_array($dataArr)) {
				foreach ($dataArr as $data) {
					if(isset($result[$data['z_zbjn']]['num'])) {
						$result[$data['z_zbjn']]['num'] += 1;
						$sum ++;
					}
				}
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
		
		echo json_encode(array(
				'result' => $result
			));
		exit;
	
	}
	
	
	/**
	 *	用户死亡等级分布（开服到选择日期）
	 */
	public function getSF(){
		$point = D(GNAME.$this->ip);
		
		//$dead = $point -> table('dead') -> field('d_level') -> where('d_time <="'.$this->enddate.'"') -> group('d_playid') -> select();
		
		$dead = $point -> table('dead') -> fquery("select * from (select * from dead where d_time <= '{$this->enddate}' ORDER BY d_level desc) as temp  GROUP BY d_playid");
		
		$result = array();  			//死亡等级分布（1~120级）
		$sum = 0;						//总人数
		
		for ( $i = 1; $i<121; $i++ ) {	//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}
		
		if (is_array($dead)) {
			foreach($dead as $k => $item) {
				if(isset($result[$item['d_level']]['num'])) {
					$result[$item['d_level']]['num'] += 1;
					$sum ++;
				}
			}
			
			foreach ($result as $k => $item) {
				if(isset($result[$k]['percent'])) {
					$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
				}
			}
		}
		
		echo json_encode(array('result' => $result));
		exit;
		
	}
	
	/**
	 *	流失任务ID
	 */
	public function getLS() {
		$point = D(GNAME.$this->ip);
		
		$loseIds = get_var_value('loseIds');	//流失用户id
		
		$result = array();  				//流失任务ID分布
		$sum = 0;							//总人数
		
		if (!empty($loseIds)) {				//计算流失用户任务ID
			$idSql = '';
			foreach($loseIds as $item) {
				$idSql .= $item['o_id'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$taskArr = $point -> table('online_sec') -> field('o_task') -> where('o_id in('.$idSql.')') -> select();
			if (is_array($taskArr)) {
				foreach ($taskArr as $task) {
					if (isset($result[$task['o_task']]['num'])) {
						$result[$task['o_task']]['num'] += 1;
					} else {
						$result[$task['o_task']] = array('num' => 1, 'percent' => 0);
					}
					$sum ++;
				}
				
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
		
		echo json_encode(array(
				'result' => $result
			));
		exit;
	
	}
	
	
	/**
	 *	流失用户等级分布
	 */
	public function getLRD() {
		$point = D(GNAME.$this->ip);
		
		$loseIds = get_var_value('loseIds');	//流失用户id
		$result = array();  					//流失任务ID分布
		$sum = 0;								//总人数

		for ( $i = 1; $i<121; $i++ ) {	//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}		
		
		if (!empty($loseIds)) {					//计算流失用户等级
			$idSql = '';
			foreach($loseIds as $item) {
				$idSql .= $item['o_id'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$taskArr = $point -> table('online_sec') -> field('o_level') -> where('o_id in('.$idSql.')') -> select();	
			if (is_array($taskArr)) {
				foreach($taskArr as $task) {
					if(isset($result[$task['o_level']]['num'])) {
						$result[$task['o_level']]['num'] += 1;
						$sum ++;
					}
				}
				
				foreach ($result as $k => $item) {
					if(isset($result[$k]['percent'])) {
						$result[$k]['percent'] = round(($result[$k]['num'] / $sum) * 100, 2);
					}
				}
			}
			
		}
		
		echo json_encode(array('result' => $result));
		exit;
	
	}
	
	
}