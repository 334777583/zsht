<?php
class login_sign{
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00501000", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
	}

	function gmlogin(){
		$flag = 1;
		//$gqid = '26396379';//用户id
		$uid = get_var_value('id') == NULL?1:get_var_value("id");
		$uip = get_var_value('ip') == NUll?1:get_var_value("ip");
		$name = get_var_value('rolename');
		$reason = get_var_value('reason');
		
		$sql="";
		//$con = mysql_connect('192.168.0.14','root','jcmysql2012!@#',true);//14服
		$con = mysql_connect('183.60.41.233','wm_76wan','wm_76wan_2013_8Hs3yTHgH',true);//76wan
		mysql_select_db("login_game",$con);//troh1
		mysql_query("SET NAMES 'utf8'",$con);
		$sql = "select name from account_data where id =".$uid;
		$res = mysql_query($sql);
		$rs= mysql_fetch_assoc($res);
		mysql_close($con);
		if(is_array($rs)){
			$gqid = $rs['name'];
		}
		$gsid = 'S1';//服务器id
		$gpt = '49you';//平台

		require_once(LOPATH.'/login/config.inc.php');
		if(!isset($GLOBALS['key'][$gpt]) && !isset($GLOBALS['setting'][$gpt][$gsid])){
		echo 'server_key fail';
		exit;
		}
		$qid = empty($_GET['qid'])?$gqid:$_GET['qid'];//用户id
		$sid = empty($_GET['sid'])?$gsid:$_GET['sid'];//服务器id
		$pingtai = empty($_GET['pt'])?$gpt:$_GET['pt'];//平台
		$times = time();
		$server_key = $GLOBALS['key'][$gpt];//生成的唯一key
		$sign = MD5("qid={$qid}&time={$times}&server_id={$sid}&pt={$pingtai}{$server_key}");//登录签名
		
		//插入数据
		//$con = mysql_connect('192.168.0.14','root','jcmysql2012!@#',true);
		$con = mysql_connect('183.60.41.233','wm_76wan','wm_76wan_2013_8Hs3yTHgH',true);//76wan
		mysql_select_db("login_game",$con);//troh1
		mysql_query("SET NAMES 'utf8'",$con);
		$sql = "insert into login_gm(l_ip,l_role_id,l_role_name,l_reason,l_operaor,l_time,l_user_id) values('".$uip."','".$uid."','".$name."','".$reason."','".$this->user['username']."','".date('Y-m-d H:i:s')."','".$gqid."')";
		$res = mysql_query($sql);
		//$rs= mysql_fetch_assoc($res);
		if(!$res){
			echo "失败";
		}else{
			echo "成功";
		}
		mysql_close($con);
		$url = "qid={$qid}&sid={$sid}&time={$times}&pt={$pingtai}&sign={$sign}&fcm=1";
		echo json_encode($url);
		/*
		http://www.a.com/login/login.php?qid=222&sid=S1&time=1382063661&pt=49you&sign=422f7d465c0fec90b91004c3f9340dbb&fcm=1
		*/
	}
	
	function getRole(){
		$obj = D('game_base');
		$time = $obj->table('back_login')->field('l_time')->order('l_time asc')->find();
		$startdate = get_var_value('startdate') == NULL?$time['l_time']:get_var_value('startdate');
		$enddate = get_var_value('enddate')== NULL?date("Y-m-d H:i:s"):get_var_value('enddate');
		$sip = get_var_value('ip');
		$gmtype = get_var_value('gmtype');
		if($gmtype == 1){ 		//一键登录
			$where = array(
				'l_time >='=>$startdate,
				'l_time <='=>$enddate
			);
			$order = 'l_id';
			$loginlist = $obj->table('back_login')->where($where)->order($order.' desc')->select();
			foreach($loginlist as $key => $value){
				$list[$key]['type'] = '一键登录';
				$list[$key]['role_name'] = $value['l_role_name'];
				$list[$key]['reason'] = $value['l_reason'];
				$list[$key]['operaor'] = $value['l_operaor'];
				$list[$key]['time'] = $value['l_time'];
			}
		}
		if($gmtype == 2){		//发送邮件
			$where = array(
				'e_time >='=>$startdate,
				'e_time <='=>$enddate
			);
			$order = 'e_id';
			$emaillist = $obj->table('email')->where($where)->order($order.' desc')->select();
			foreach($emaillist as $key => $value){
				$list[$key]['type'] = '发送邮件';
				$list[$key]['role_name'] = $value['e_name'];
				$list[$key]['reason'] = $value['e_reason'];
				$list[$key]['operaor'] = $value['e_operaor'];
				$list[$key]['time'] = $value['e_time'];
			}
		}
		if($gmtype == 3){			//发送公告
			$where = array(
				'n_date >='=>$startdate,
				'n_date <='=>$enddate
			);
			$order = 'n_id';
			$newlist = $obj->table('news')->where($where)->order($order.' desc')->select();
			foreach($newlist as $key => $value){
				$list[$key]['type'] = '发送公告';
				$list[$key]['role_name'] = '即时公告';
				$list[$key]['reason'] = $value['n_content'];
				$list[$key]['operaor'] = $value['n_operaor'];
				$list[$key]['time'] = $value['n_date'];
			}
		}
		if($gmtype == 4){			//冻结
			$where = array(
				'f_inserttime >='=>$startdate,
				'f_inserttime <='=>$enddate
			);
			$order = 'f_id';
			$freelist = $obj->table('freeze')->where($where)->order($order.' desc')->select();
			foreach($freelist as $key => $value){
				$list[$key]['type'] = '冻结';
				$list[$key]['role_name'] = $value['f_role_name'];
				$list[$key]['reason'] = $value['f_reason'];
				$list[$key]['operaor'] = $value['f_operaor'];
				$list[$key]['time'] = $value['f_inserttime'];
			}
		}
		if($gmtype == 5){			//下线
			$where = array(
				'f_inserttime >='=>$startdate,
				'f_inserttime <='=>$enddate
			);
			$order = 'f_id';
			$linelist = $obj->table('offline')->where($where)->order($order.' desc')->select();
			foreach($linelist as $key => $value){
				$list[$key]['type'] = '踢下线';
				$list[$key]['role_name'] = $value['f_role_name'];
				$list[$key]['reason'] = $value['f_reason'];
				$list[$key]['operaor'] = $value['f_operaor'];
				$list[$key]['time'] = $value['f_inserttime'];
			}
		}
		echo json_encode(array(
			'result'=>$list,
			'startDate'=>$startdate,
			'endDate'=>$enddate
		));
		
	}
}
?>