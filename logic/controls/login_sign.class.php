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
		$uid = get_var_value('id') == NULL?1:get_var_value("id");
		$uip = get_var_value('ip') == NUll?1:get_var_value("ip");
		$name = get_var_value('rolename');
		$reason = get_var_value('reason');
		$sql="";
		//$con = mysql_connect('192.168.0.14','root','jcmysql2012!@#',true);//14服
		$con = mysql_connect('183.60.41.228:3307','dzs49yous001a','www1938THgHy8Hs_g938938938',true);//76wan
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
		require_once(TPATH.'/login/config.inc.php');
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
		$obj =D("game_info");
		$obj -> table('back_login')->insert(array(
												'l_ip'=>$uip,
												'l_role_id'=>$uid,
												'l_role_name'=>$name,
												'l_reason'=>$reason,
												'l_operaor'=>$this->user['username'],
												'l_time'=>date('Y-m-d H:i:s'),
												'l_user_id'=>$gqid
											));
		$url = "qid={$qid}&sid={$sid}&time={$times}&pt={$pingtai}&sign={$sign}&fcm=1";
		echo json_encode($url);
		/*
		http://www.a.com/login/login.php?qid=222&sid=S1&time=1382063661&pt=49you&sign=422f7d465c0fec90b91004c3f9340dbb&fcm=1
		*/
	}
	
	function getRole(){
		$obj = D("game_info");
		$star = $obj->table('back_login')->field('')->order('l_time asc')->limit(0,1)->find();
		$startdate = get_var_value('startdate')==NULL?$star['l_time']:get_var_value('startdate');
		$enddate = get_var_value('enddate')==NULL?date("Y-m-d H:i:s"):get_var_value('enddate');
		$sip = get_var_value('ip');
		$rs = $obj->table("back_login")->where(array('l_ip'=>$sip,'l_time>'=>$startdate,'l_time<'=>$enddate))->order('l_time desc')->select();
		echo json_encode(array('result'=>$rs,'startDate'=>$startdate,'endDate'=>$enddate));
	}
}
?>