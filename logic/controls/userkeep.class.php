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
			if(!in_array('00100200', $this->user['code'])){
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
				$keepIds[] = array('GUID' => $keep['u_oid'],'userid' => $keep['u_userid']);
			}	
		}
		if($lose_temp != '') {
			foreach($lose_temp as $lose) {
				$loseIds[] = array('GUID' => $lose['u_oid'],'userid' => $lose['u_userid']);
			}	
		}
	}
	
	/**
	 * 留存等级分布
	 */
	public function getLD() {
		$point = D('game'.$this->ip);
		
		$loseIds = array();					//流失用户id
		$keepIds = array();					//留存用户id
		
		$this->getRecordByTemp($loseIds, $keepIds, $this->enddate, $point); //先从缓存表取数据
		if(empty($loseIds) && empty($keepIds)) {							//缓存表不存在则重新计算
			
			$bNine = strtotime(date("Y-m-d",strtotime($this->enddate) - 86400*8));
			$bEight = strtotime(date("Y-m-d",strtotime($this->enddate) - 86400*7));	//选择日期前八天到所选日期7天内没有登录过的为流失用户,反之为留存用户
			$bSeven = strtotime(date("Y-m-d",strtotime($this->enddate) - 86400*6));
			$btoday = strtotime(date('Y-m-d',(strtotime($this->enddate) + 86400)));
			
			$obj =D('game');
			$ulist = "SELECT GUID FROM (SELECT * FROM player_table where LoginTime >= '".$bNine."' and LoginTime >='".$bEight."' ORDER BY LoginTime desc) as temp GROUP BY GUID";
			//八天前那天的登录用户
			$users = $obj -> table('player_table') -> fquery($ulist);		
			//往后七天的登录用户
			$seven_user = $obj -> table('player_table') -> fquery("SELECT GUID FROM (SELECT * FROM player_table where LoginTime >= '{$bSeven}' and LoginTime < '{$btoday}' ORDER BY LoginTime desc) as temp GROUP BY GUID");	
			if (is_array($users)) {
				foreach ($users as $user) {			//计算流失用户和留存用户
					$flag = 1;						//标记该用户是否流失用户（1：是，0：不是）
					foreach($seven_user as $seven) {
						if( $user['GUID'] ==  $seven['GUID']) {
							 $keepIds[] = array('GUID' => $seven['GUID'],'userid' => $seven['GUID']);
							 $flag = 0;
							 break;
						}
					}
					if($flag) {
						$loseIds[] = array('GUID' => $user['GUID'],'userid' => $user['GUID']);
					}
				}
				
				// if($this->enddate < date("Y-m-d")) {	//历史数据才进缓存表
					// $k_insert_sql = '';					
					// $l_insert_sql = '';
					// $expire = date("Y-m-d", strtotime("+2 day"));
					// foreach($keepIds as $keep) {
						// $k_insert_sql .= "('" . $this->enddate . "','" . $keep['GUID'] . "','" . $keep['userid'] . "',". "0" . ",'" . $expire .  "'),"; 
					// }
					
					// foreach($loseIds as $lose) {
						// $l_insert_sql .= "('" . $this->enddate . "','" . $lose['GUID'] . "','" . $lose['userid']  . "',". "1" . ",'" . $expire .  "'),"; 
						// break;
					// }
					
					// if($k_insert_sql !== '') {
						// $k_insert_sql = rtrim($k_insert_sql, ',');
						// $k_insert_sql .= ';';
						// $sql = 'insert into user_temp(u_date,u_oid,u_userid,u_type,u_expire) values ' . $k_insert_sql;
						// @$obj -> table('user_temp') -> fquery($sql);
						// unset($k_insert_sql);
						// unset($sql);
					// }
					
					// if($l_insert_sql !== '') {
						// $l_insert_sql = rtrim($l_insert_sql, ',');
						// $l_insert_sql .= ';';
						// $sql = 'insert into user_temp(u_date,u_oid,u_userid,u_type,u_expire) values ' . $l_insert_sql;
						// @$obj -> table('user_temp') -> fquery($sql);
						// unset($l_insert_sql);
						// unset($sql);
					// }	
				//}
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
				$idSql .= $item['GUID'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$ld = $obj -> table('player_table') -> field('Level, GUID') -> where('GUID in ('.$idSql.')') ->select();   // 留存等级
			if	(is_array($ld)) {
				foreach ($ld as $item) {
					if(isset($result[$item['Level']]['num'])) {
						$result[$item['Level']]['num'] += 1;
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
			'keepIds' => $keepIds
		));
		exit;
	
	}
	
	
	
	/**
	 *	留存用户装备强化等级分布（武器，头部，衣服，披风，项链，护腕，戒指，鞋子）
	 */
	public function getTBQH() {
		$point = D('game'.$this->ip);
		
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
		$point = D('game'.$this->ip);
		
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
		$point = D('game'.$this->ip);
		
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
		$point = D('game'.$this->ip);
		
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
		$point = D('game'.$this->ip);
		
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
		$point = D('game'.$this->ip);
		
		$loseIds = get_var_value('loseIds');	//流失用户id
		
		$result = array();  				//流失任务ID分布
		$sum = 0;							//总人数
		
		if (!empty($loseIds)) {				//计算流失用户任务ID
			$idSql = '';
			foreach($loseIds as $item) {
				$idSql .= $item['GUID'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$taskArr = $point -> table('player_table') -> field('o_task') -> where('GUID in('.$idSql.')') -> select();
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
		$point = D('game'.$this->ip);
		
		$loseIds = get_var_value('loseIds');	//流失用户id
		$result = array();  					//流失任务ID分布
		$sum = 0;								//总人数

		for ( $i = 1; $i<121; $i++ ) {	//初始化，默认为0;
			$result[$i] = array('num' => 0, 'percent' => 0);
		}		
		
		if (!empty($loseIds)) {					//计算流失用户等级
			$idSql = '';
			foreach($loseIds as $item) {
				$idSql .= $item['GUID'] . ',';
			}
			$idSql = rtrim($idSql, ',');
			
			$taskArr = $point -> table('player_table') -> field('o_level') -> where('GUID in('.$idSql.')') -> select();	
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