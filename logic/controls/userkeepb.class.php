<?php
/**
 * FileName: userkeep.class.php
 * Description:留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-18 14:20:29
 * Version:1.00
 */
class userkeepb{
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
		$this->enddate =  get_var_value('enddate');// == NULL? '' : get_var_value('enddate');
		$this->startdate =  get_var_value('startdate');// == NULL? '' : get_var_value('startdate');
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

	public function getstartTime(){
		
		//list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		$listdate = $point -> table("game_user") -> field('Createtime') -> order('Createtime asc')-> limit(0,1) ->find();
		echo date('Y-m-d',$listdate['Createtime']);
	}
	
	public function getResult(){ //获取所有统计的数据
		$point = D('game'.$_POST['sip']);
		$enddate = date("Y-m-d",strtotime($this->enddate)+86400);
		//查询当前日期
		//$listdata = $point->fquery("SELECT COUNT(c_id) dataC,c_time  FROM creat_success WHERE c_time BETWEEN '{$_POST['startdate']}' AND '{$enddate}' GROUP BY date_format(c_time,'%Y-%m-%d')");
		
		$creatrole="SELECT * FROM creat_success WHERE c_time BETWEEN '{$this->startdate}' AND '{$enddate}' GROUP BY date_format(c_time,'%Y-%m-%d')";
		//创角数
		$listdata = $point->fquery("SELECT COUNT(c_id) dataC,c_time  FROM creat_success WHERE c_time BETWEEN '{$this->startdate}' AND '{$enddate}' GROUP BY date_format(c_time,'%Y-%m-%d')");
				
		//查询第二天统计数据
			$secondS = date("Y-m-d",strtotime($_POST['startdate'])+86400);
			$secondE = date("Y-m-d",strtotime($_POST['enddate'])+86400*2);
			$onlinerole = "select * from online_sec where o_date BETWEEN '{$secondS}' AND '{$secondE}'";
		
			//$seconddata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			//$selist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_time from online_sec o inner join creat_success c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			// $selist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_time from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			// $selist = "select COUNT(c.c_id) dataC,o.o_userid,o.o_date,c.c_time from ({$onlinerole}) o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE c.c_time BETWEEN '{$this->startdate}' AND '{$enddate}' GROUP BY date_format(c.c_time,'%Y-%m-%d')";
			$selist = "select COUNT(c.c_id) dataC,o.o_userid,o.o_date,c.c_time from ({$creatrole}) c inner join ({$onlinerole}) o on o.o_userid = c.c_playid WHERE c.c_time BETWEEN '{$this->startdate}' AND '{$enddate}' GROUP BY date_format(c.c_time,'%Y-%m-%d'), c.c_playid";
			
			$seconddata = $point->fquery($selist);
			// print_R($point);
			// exit;
			foreach($seconddata as $key => $value){
				if($seconddata[$key]['dataC'] < $listdata[$key]['dataC']){
					$psec[$key]['dataC'] = sprintf('%0.2f',($seconddata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$psec[$key]['dataC'] = 100;
				}
			}
			
			
		// print_r($seconddata);
		// die;

		//查询第三天数据统计
		
			$thrS = date("Y-m-d",strtotime($_POST['startdate'])+86400*2);
			$thrE = date("Y-m-d",strtotime($_POST['enddate'])+86400*3);
			//$thrdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$thlist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$thrdata = $point->fquery($thlist);
			foreach($thrdata as $key => $value){
				if($thrdata[$key]['dataC'] < $listdata[$key]['dataC']){
					$pthr[$key]['dataC'] = sprintf('%0.2f',($thrdata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$pthr[$key]['dataC'] = 100;
				}
			}
		//查询第四天数据统计
		
			$fourS = date("Y-m-d",strtotime($_POST['startdate'])+86400*3);
			$fourE = date("Y-m-d",strtotime($_POST['enddate'])+86400*4);
			//$fourdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$folist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$fourdata = $point->fquery($folist);
			foreach($fourdata as $key => $value){
				if($fourdata[$key]['dataC'] < $listdata[$key]['dataC']){
					$pfou[$key]['dataC'] = sprintf('%0.2f',($fourdata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$pfou[$key]['dataC'] = 100;
				}
			}

		//查询第五天数据统计
		
			$fiveS = date("Y-m-d",strtotime($_POST['startdate'])+86400*4);
			$fiveE = date("Y-m-d",strtotime($_POST['enddate'])+86400*5);
			//$fivedata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$filist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$fivedata = $point->fquery($filist);
			foreach($fivedata as $key => $value){
				if($fivedata[$key]['dataC'] < $listdata[$key]['dataC']){
					$pfiv[$key]['dataC'] = sprintf('%0.2f',($fivedata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$pfiv[$key]['dataC'] = 100;
				}
			}
		

		//查询第六天数据统计
		
			$sixS = date("Y-m-d",strtotime($_POST['startdate'])+86400*5);
			$sixE = date("Y-m-d",strtotime($_POST['enddate'])+86400*6);
			//$sixdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$silist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$sixdata = $point->fquery($silist);
			foreach($sixdata as $key => $value){
				if($sixdata[$key]['dataC'] < $listdata[$key]['dataC']){
					$psix[$key]['dataC'] = sprintf('%0.2f',($sixdata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$psix[$key]['dataC'] = 100;
				}
			}

		//查询第七天数据统计
		
			$sevenS = date("Y-m-d",strtotime($_POST['startdate'])+86400*6);
			$sevenE = date("Y-m-d",strtotime($_POST['enddate'])+86400*7);
			//$sevendata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$sevlist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$sevendata = $point->fquery($sevlist);
			foreach($sevendata as $key => $value){
				if($sevendata[$key]['dataC'] < $listdata[$key]['dataC']){
					$psev[$key]['dataC'] = sprintf('%0.2f',($sevendata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$psev[$key]['dataC'] = 100;
				}
			}
		
		//查询双周数据统计
		
			$weekS = date("Y-m-d",strtotime($_POST['startdate'])+86400*13);
			$weekE = date("Y-m-d",strtotime($_POST['enddate'])+86400*14);
			//$weekdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$welist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$weekdata = $point->fquery($welist);
			foreach($weekdata as $key => $value){
				if($weekdata[$key]['dataC'] < $listdata[$key]['dataC']){
					$pwee[$key]['dataC'] = sprintf('%0.2f',($weekdata[$key]['dataC']/$listdata[$key]['dataC'])*100);
				}else{
					$pwee[$key]['dataC'] = 100;
				}
			}

		//查询30天保留数据统计
		
			$monS = date("Y-m-d",strtotime($_POST['startdate'])+86400*29);
			$monE = date("Y-m-d",strtotime($_POST['enddate'])+86400*30);
			//$mondata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
			$molist = "select COUNT(o.o_id) dataC,o.o_userid,o.o_date,c.c_playid from online_sec o inner join ({$creatrole}) c on o.o_userid = c.c_playid WHERE o.o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o.o_date,'%Y-%m-%d')";
			$mondata = $point->fquery($molist);
			if(!empty($mondata)){
				foreach($mondata as $key => $value){
					if($mondata[$key]['dataC'] < $listdata[$key]['dataC']){
						$pmon[$key]['dataC'] = sprintf('%0.2f',($mondata[$key]['dataC']/$listdata[$key]['dataC'])*100);
					}else{
						$pmon[$key]['dataC'] = 100;
					}
				}
			}else{
				$pmon = 0;
			}
			// print_R($listdata);
			$return_Arr = array('listdata'=>$listdata,
								'seconddata'=>$seconddata,
								'thrdata'=>$thrdata,
								'fourdata'=>$fourdata,
								'fivedata'=>$fivedata,
								'sixdata'=>$sixdata,
								'sevendata'=>$sevendata,
								'weekdata'=>$weekdata,
								'mondata'=>$mondata,
								
								'seco'=>$psec,
								'thco'=>$pthr,
								'foco'=>$pfou,
								'fico'=>$pfiv,
								'sico'=>$psix,
								'sevco'=>$psev,
								'weco'=>$pwee,
								'moco'=>$pmon
								);
			if (!empty($listdata)) {
				 ECHO json_encode($return_Arr);
			}else{
				echo 1;
			}
		/*
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		//查询当前日期
		$startdate = strtotime($_POST['startdate'].'00:00:00');
		$enddate = strtotime($_POST['enddate'].'23:59:59');
		$listdata = $point->fquery("SELECT COUNT(GUID) cid,FROM_UNIXTIME(LoginTime,'%Y-%m-%d') as time FROM player_table WHERE LoginTime BETWEEN {$startdate} AND {$enddate} GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
		//查询第二天统计数据
			$secondS = strtotime($_POST['startdate'].'00:00:00')+86400;
			$secondE = strtotime($_POST['startdate'].'23:59:59')+86400;
			$seconddata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN {$secondS} AND {$secondE} GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')  ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($seconddata)){
				$seconddata[0]['cid']=0;
			}
		//查询第三天数据统计
			$thrS = strtotime($_POST['startdate'].'00:00:00')+86400*2;
			
			$thrE = strtotime($_POST['startdate'].'23:59:59')+86400*2;
			$thrdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($thrdata)){
				$thrdata[0]['cid']=0;
			}
			
		//查询第四天数据统计
			$fourS = strtotime($_POST['startdate'].'00:00:00')+86400*3;
			$fourE = strtotime($_POST['startdate'].'23:59:59')+86400*3;
			$fourdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($fourdata)){
				$fourdata[0]['cid']=0;
			}
		
		//查询第五天数据统计
			$fiveS = strtotime($_POST['startdate'].'00:00:00')+86400*4;
			$fiveE = strtotime($_POST['startdate'].'23:59:59')+86400*4;
			$fivedata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($fivedata)){
				$fivedata[0]['cid']=0;
			}
			
		//查询第六天数据统计
			$sixS = strtotime($_POST['startdate'].'00:00:00')+86400*5;
			$sixE = strtotime($_POST['startdate'].'23:59:59')+86400*5;
			$sixdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($sixdata)){
				$sixdata[0]['cid']=0;
			}
			
		//查询第七天数据统计
			$sevenS = strtotime($_POST['startdate'].'00:00:00')+86400*6;
			$sevenE = strtotime($_POST['startdate'].'23:59:59')+86400*6;
			$sevendata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($sevendata)){
				$sevendata[0]['cid']=0;
			}
			
		//查询双周数据统计
			$weekS = strtotime($_POST['startdate'].'00:00:00')+86400*13;
			$weekE = strtotime($_POST['enddate'].'23:59:59')+86400*13;
			$weekdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($weekdata)){
				$weekdata[0]['cid']=0;
			}
			
		//查询30天保留数据统计
			$monS = strtotime($_POST['startdate'].'00:00:00')+86400*29;
			$monE = strtotime($_POST['enddate'].'23:59:59')+86400*29;
			$mondata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime > '{$monS}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ORDER BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d') ASC");
			if(empty($mondata)){
				$mondata[0]['cid']=0;
			}
			

			$return_Arr = array('listdata'=>$listdata,'seconddata'=>$seconddata,'thrdata'=>$thrdata,'fourdata'=>$fourdata,'fivedata'=>$fivedata,'sixdata'=>$sixdata,'sevendata'=>$sevendata,'weekdata'=>$weekdata,'mondata'=>$mondata);
			if (!empty($listdata)) {
				 echo json_encode($return_Arr);
			}else{
				echo 1;
			}
		 */

	}

	public function getImgResult(){ //获取所有统计的图表数据
	
		$point = D('game'.$_POST['sip']);
		//查询当前日期
		$listdata = $point->fquery("SELECT COUNT(o_id) dataC,date_format(o_date,'%Y-%m-%d') Cdate  FROM online_sec WHERE o_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		//查询第二天统计数据
		
			$secondS = date("Y-m-d",strtotime($_POST['startdate'])+86400);
			$secondE = date("Y-m-d",strtotime($_POST['enddate'])+86400);
			$seconddata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第三天数据统计
		
			$thrS = date("Y-m-d",strtotime($_POST['startdate'])+86400*2);
			$thrE = date("Y-m-d",strtotime($_POST['enddate'])+86400*2);
			$thrdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第四天数据统计
		
			$fourS = date("Y-m-d",strtotime($_POST['startdate'])+86400*3);
			$fourE = date("Y-m-d",strtotime($_POST['enddate'])+86400*3);
			$fourdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第五天数据统计
		
			$fiveS = date("Y-m-d",strtotime($_POST['startdate'])+86400*4);
			$fiveE = date("Y-m-d",strtotime($_POST['enddate'])+86400*4);
			$fivedata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		

		//查询第六天数据统计
		
			$sixS = date("Y-m-d",strtotime($_POST['startdate'])+86400*5);
			$sixE = date("Y-m-d",strtotime($_POST['enddate'])+86400*5);
			$sixdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第七天数据统计
		
			$sevenS = date("Y-m-d",strtotime($_POST['startdate'])+86400*6);
			$sevenE = date("Y-m-d",strtotime($_POST['enddate'])+86400*6);
			$sevendata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		//查询双周数据统计
		
			$weekS = date("Y-m-d",strtotime($_POST['startdate'])+86400*13);
			$weekE = date("Y-m-d",strtotime($_POST['enddate'])+86400*13);
			$weekdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询30天保留数据统计
		
			$monS = date("Y-m-d",strtotime($_POST['startdate'])+86400*29);
			$monE = date("Y-m-d",strtotime($_POST['enddate'])+86400*29);
			$mondata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		$data_Arr = array();
		$str_arr = array();
		$string_arr = array();
		error_reporting(4);

		
			for ($i=0; $i <count($listdata); $i++) { 
				$str_arr[$i][] = '"time":"'.$listdata[$i]['Cdate'].'"';
				if(!empty($seconddata)){
					if ($i < count($seconddata)) {
						$str_arr[$i][] = '"second":'.$seconddata[$i]['dataC'];
					}
				}

				if(!empty($thrdata)){
					if ($i < count($thrdata)) {
						$str_arr[$i][] = '"thr":'.$thrdata[$i]['dataC'];
					}
				}

				if(!empty($fourdata)){
					if ($i < count($fourdata)) {
						$str_arr[$i][] = '"four":'.$fourdata[$i]['dataC'];
					}
				}

				if(!empty($fivedata)){
					if ($i < count($fivedata)) {
						$str_arr[$i][] = '"five":'.$fivedata[$i]['dataC'];
					}
				}

				if(!empty($sixdata)){
					if ($i < count($sixdata)) {
						$str_arr[$i][] = '"six":'.$sixdata[$i]['dataC'];
					}
				}

				if(!empty($sevendata)){
					if ($i < count($sevendata)) {
						$str_arr[$i][] = '"seven":'.$sevendata[$i]['dataC'];
					}
				}

				if(!empty($weekdata)){
					if ($i < count($weekdata)) {
						$str_arr[$i][] = '"week":'.$weekdata[$i]['dataC'];
					}
				}

				if(!empty($mondata)){
					if ($i < count($mondata)) {
						$str_arr[$i][] = '"mon":'.$mondata[$i]['dataC'];
					}
				}
				$string_arr[] = implode(',', $str_arr[$i]);
			}
			echo $str3 = "[{".implode('},{', $string_arr).'}]';
		/*
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		//查询当前日期
		$startdate = strtotime($_POST['startdate'].'00:00:00');
		$enddate = strtotime($_POST['startdate'].'23:59:59');
		$listdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime FROM player_table WHERE LoginTime BETWEEN {$startdate} AND {$enddate} GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		foreach ($listdata as $key => $value) {
			$listdata[$key]['LoginTime'] = date('Y-m-d',$value['LoginTime']);
		}

		//查询第二天统计数据
			$secondS = strtotime($_POST['startdate'].'00:00:00')+86400;
			$secondE = strtotime($_POST['startdate'].'23:59:59')+86400;
			$seconddata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");

		//查询第三天数据统计
			$thrS = strtotime($_POST['startdate'].'00:00:00')+86400*2;
			$thrE = strtotime($_POST['startdate'].'23:59:59')+86400*2;
			$thrdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第四天数据统计
			$fourS = strtotime($_POST['startdate'].'00:00:00')+86400*3;
			$fourE = strtotime($_POST['startdate'].'23:59:59')+86400*3;
			$fourdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第五天数据统计
			$fiveS = strtotime($_POST['startdate'].'00:00:00')+86400*4;
			$fiveE = strtotime($_POST['startdate'].'23:59:59')+86400*4;
			$fivedata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第六天数据统计
			$sixS = strtotime($_POST['startdate'].'00:00:00')+86400*5;
			$sixE = strtotime($_POST['startdate'].'23:59:59')+86400*5;
			$sixdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第七天数据统计
			$sevenS = strtotime($_POST['startdate'].'00:00:00')+86400*6;
			$sevenE = strtotime($_POST['startdate'].'23:59:59')+86400*6;
			$sevendata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询双周数据统计
			$weekS = strtotime($_POST['startdate'].'00:00:00')+86400*13;
			$weekE = strtotime($_POST['enddate'].'23:59:59')+86400*13;
			$weekdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询30天保留数据统计
			$monS = strtotime($_POST['startdate'].'00:00:00')+86400*29;
			$monE = strtotime($_POST['enddate'].'23:59:59')+86400*29;
			$mondata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime > '{$monS}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		

		$data_Arr = array();
		$str_arr = array();
		$string_arr = array();
		error_reporting(4);

		
			for ($i=0; $i <count($listdata); $i++) { 
				$str_arr[$i][] = '"time":"'.$listdata[$i]['LoginTime'].'"';
				if(!empty($seconddata)){
					if ($i < count($seconddata)) {
						$str_arr[$i][] = '"second":'.$seconddata[$i]['cid'];
					}
				}

				if(!empty($thrdata)){
					if ($i < count($thrdata)) {
						$str_arr[$i][] = '"thr":'.$thrdata[$i]['cid'];
					}
				}

				if(!empty($fourdata)){
					if ($i < count($fourdata)) {
						$str_arr[$i][] = '"four":'.$fourdata[$i]['cid'];
					}
				}

				if(!empty($fivedata)){
					if ($i < count($fivedata)) {
						$str_arr[$i][] = '"five":'.$fivedata[$i]['cid'];
					}
				}

				if(!empty($sixdata)){
					if ($i < count($sixdata)) {
						$str_arr[$i][] = '"six":'.$sixdata[$i]['cid'];
					}
				}

				if(!empty($sevendata)){
					if ($i < count($sevendata)) {
						$str_arr[$i][] = '"seven":'.$sevendata[$i]['cid'];
					}
				}

				if(!empty($weekdata)){
					if ($i < count($weekdata)) {
						$str_arr[$i][] = '"week":'.$weekdata[$i]['cid'];
					}
				}

				if(!empty($mondata)){
					if ($i < count($mondata)) {
						$str_arr[$i][] = '"mon":'.$mondata[$i]['cid'];
					}
				}
				$string_arr[] = implode(',', $str_arr[$i]);
			}
			echo $str3 = "[{".implode('},{', $string_arr).'}]';
			*/
		
	}

	public function writeExcel(){
		
    	global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		//查询当前日期
		$listdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");

		//查询第二天统计数据
			$secondS = strtotime($_POST['startdate'].'00:00:00')+86400;
			$secondE = strtotime($_POST['startdate'].'23:59:59')+86400;
			$seconddata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");

		//查询第三天数据统计
			$thrS = strtotime($_POST['startdate'].'00:00:00')+86400*2;
			$thrE = strtotime($_POST['startdate'].'23:59:59')+86400*2;
			$thrdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第四天数据统计
			$fourS = strtotime($_POST['startdate'].'00:00:00')+86400*3;
			$fourE = strtotime($_POST['startdate'].'23:59:59')+86400*3;
			$fourdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第五天数据统计
			$fiveS = strtotime($_POST['startdate'].'00:00:00')+86400*4;
			$fiveE = strtotime($_POST['startdate'].'23:59:59')+86400*4;
			$fivedata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第六天数据统计
			$sixS = strtotime($_POST['startdate'].'00:00:00')+86400*5;
			$sixE = strtotime($_POST['startdate'].'23:59:59')+86400*5;
			$sixdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询第七天数据统计
			$sevenS = strtotime($_POST['startdate'].'00:00:00')+86400*6;
			$sevenE = strtotime($_POST['startdate'].'23:59:59')+86400*6;
			$sevendata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询双周数据统计
			$weekS = strtotime($_POST['startdate'].'00:00:00')+86400*13;
			$weekE = strtotime($_POST['enddate'].'23:59:59')+86400*13;
			$weekdata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		
		//查询30天保留数据统计
			$monS = strtotime($_POST['startdate'].'00:00:00')+86400*29;
			$monE = strtotime($_POST['enddate'].'23:59:59')+86400*29;
			$mondata = $point->fquery("SELECT COUNT(GUID) cid,LoginTime  FROM player_table WHERE LoginTime BETWEEN '{$monS}' AND '{$monE}' GROUP BY FROM_UNIXTIME(LoginTime,'%Y-%m-%d')");
		

	
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '新登陆账号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '次日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '三日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '四日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '五日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '六日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '七日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '双周日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '30日留存率');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($listdata)) {
			foreach($listdata as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item['o_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item['cid']);
				if ($seconddata != 1) {
					if($k < count($seconddata)){
						
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), round($seconddata[$k]['cid']/$item['cid']*100,2).' %('.$seconddata[$k]['cid'].')');
					}
				}

				if ($thrdata != 1) {
					if($k < count($thrdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), round($thrdata[$k]['cid']/$item['cid']*100,2).' %('.$thrdata[$k]['cid'].')');
					}
				}

				if ($fourdata != 1) {
					if($k < count($fourdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), round($fourdata[$k]['cid']/$item['cid']*100,2).' %('.$fourdata[$k]['cid'].')');
					}
				}


				if ($fivedata != 1) {
					if($k < count($fivedata)){
						$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), round($fivedata[$k]['cid']/$item['cid']*100,2).' %('.$fivedata[$k]['cid'].')');
					}
				}
				if ($sixdata != 1) {
					if($k < count($sixdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), round($sixdata[$k]['cid']/$item['cid']*100,2).' %('.$sixdata[$k]['cid'].')');
					}
				}
				if ($sevendata != 1) {
					if($k < count($sevendata)){
						$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), round($sevendata[$k]['cid']/$item['cid']*100,2).' %('.$sevendata[$k]['cid'].')');
					}
				}
				if ($weekdata != 1) {
					if($k < count($weekdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), round($weekdata[$k]['cid']/$item['cid']*100,2).' %('.$weekdata[$k]['cid'].')');
					}
				}

				if (!empty($mondata)) {
					if($k < count($mondata)){
						$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), round($mondata[$k]['cid']/$item['cid']*100,2).' %('.$mondata[$k]['cid'].')');
					}
				}

			}
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "留存分析_".date('Y_m_d H_i_s');
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
	}
}