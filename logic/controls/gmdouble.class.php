<?php
/**
 * FileName: gmdouble.class.php
 * Description:用户管理工具(GM)-道具申请
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */
class gmdouble{
	
	/**
	 * 发送原因
	 * @var string
	 */
	public $reason;
	
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00500800", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL?-1:get_var_value('ip');
		$this->pageSize = get_var_value('pageSize') == NULL?10:get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL?1:get_var_value('curPage');
		$this->type = get_var_value('type') == NULL?0:get_var_value('type');
		$this->interval =  get_var_value('interval') == NULL?0:get_var_value('interval');
		$this->starttime = get_var_value('starttime') == NULL?'':get_var_value('starttime');
		$this->endtime =  get_var_value('endtime') == NULL?'':get_var_value('endtime');
		$this->content =  get_var_value('content', false) == NULL?'' : get_var_value('content', false);
		$this->gid =  get_var_value('gid') == NULL?'':get_var_value('gid');
	}
	
	/**
	 * 道具申请
	 */
	public function douexper(){
		$gold = get_var_value('gold');
		$danger = get_var_value('danger');
		$safe = get_var_value('safe');
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$ip = get_var_value('ip');
		$reason = get_var_value('reason');
		$again = get_var_value('again');
		$obj = D('game_info');
		$sy = date('Ymd',strtotime($startdate));
		$sh = date('His',strtotime($startdate));
		$start = $sy."%20".$sh;
		$ey = date('Ymd',strtotime($enddate));
		$eh = date('His',strtotime($enddate));
		$end = $ey."%20".$eh;
		
		if(!empty($safe) || !empty($danger) && !empty($gold)){
			$ex = '';
			$bao = '';
			$mon = '';
			$jin = '';
			$add = array();
			$jin = explode(",",$gold);
			foreach($jin as $key =>$value){
				if(in_array('经验',$jin)){
					$add['ex']=$again;
				}else{
					$add['ex']=0;
				}
				if(in_array('掉宝',$jin)){
					$add['bao']=$again;
				}else{
					$add['bao']=0;
				}
				if(in_array('掉金',$jin)){
					$add['mon']=$again;
				}else{
					$add['mon']=0;
				}
			}
			$type = array();
			$scid = array();
			$dan = explode(",",$danger);
			foreach($dan as $key => $value){
				if(trim($dan[$key]) == '峨眉山' ){
				$scid['ems']=1;
				}
				if(trim($dan[$key]) == '终南山' ){
				$scid['zns']=2;
				}
				if(trim($dan[$key]) == '绝情谷' ){
				$scid['jqg'] = 3;
				}
				if(trim($dan[$key]) == '大都' ){
				$scid['dd'] = 4;
				}
				if(trim($dan[$key]) == '桃花岛' ){
				$scid['thd'] = 5;
				}
				if(trim($dan[$key]) == '华山' ){
				$scid['hs'] =6;
				}
				if(trim($dan[$key]) == '汝阳王府' ){
				$scid['rywf'] = 7;
				}
				if(trim($dan[$key]) == '蝴蝶谷' ){
				$scid['hdg'] = 8;
				}
				if(trim($dan[$key]) == '武当山' ){
				$scid['wds'] = 9;
				}
				if(trim($dan[$key]) == '昆仑山' ){
				$scid['kls']=10;
				}
				if(trim($dan[$key]) == '光明顶' ){
				$scid['gmd']=11;
				}
				if(trim($dan[$key]) == '元军营寨' ){
				$scid['yjyz'] = 30;
				}
				if(trim($dan[$key]) == '万安寺' ){
				$scid['was'] = 31;
				}
				if(trim($dan[$key]) == '武当别院' ){
				$scid['wdby'] = 32;
				}
				if(trim($dan[$key]) == '帮会战场' ){
				$scid['bhzc'] = 40;
				}
				if(trim($dan[$key]) == '名动江湖' ){
				$scid['mdjh'] = 41;
				}
				if(trim($dan[$key]) == '护送出关' ){
				$scid['hscg'] = 42;
				}
				if(trim($dan[$key]) == '太湖水贼' ){
				$scid['dhsj'] = 43;
				}
				if(trim($dan[$key]) == '剑冢' ){
				$scid['jj']=20;
				}
				if(trim($dan[$key]) == '绝情深涧' ){
				$scid['jqsj'] = 26;
				}
				if(trim($dan[$key]) == '桃花阵' ){
				$scid['thz'] = 24;
				}
				if(trim($dan[$key]) == '华山之巅' ){
				$scid['hszd'] = 25;
				}
				if(trim($dan[$key]) == '万安塔' ){
				$scid['wat'] = 27;
				}
				if(trim($dan[$key]) == '药王谷' ){
				$scid['ywg'] = 28;
				}
				if(trim($dan[$key]) == '真武七截阵' ){
				$scid['zwq'] = 29;
				}
				if(trim($dan[$key]) == '昆仑仙谷' ){
				$scid['hlxg'] = 33;
				}
				if(trim($dan[$key]) == '明教密道' ){
				$scid['mjmd']=34;
				}
				if(trim($dan[$key]) == '古墓奇缘1' ){
				$scid['gma']=21;
				}
				if(trim($dan[$key]) == '古墓奇缘2' ){
				$scid['gmb'] = 22;
				}
				if(trim($dan[$key]) == '武林名宿第' ){
				$scid['wlmx'] = 23;
				}
			}
			$map = implode(",",$scid);
			$saf = explode(",",$safe);
			foreach($saf as $key => $value){
				if(trim($saf[$key]) == '全地图(除副本)' ){
				$type['dr']=0;
				}
				if(trim($saf[$key]) == '单人副本' ){
				$type['dr']=1;
				}
				if(trim($saf[$key]) == '盗王陵' ){
				$type['dw'] = 2;
				}
				if(trim($saf[$key]) == '独孤求败' ){
				$type['dg'] = 3;
				}
				if(trim($saf[$key]) == '大湖水怪' ){
				$type['dh'] = 4;
				}
				if(trim($saf[$key]) == '名动江湖' ){
				$type['md'] = 5;
				}
				if(trim($saf[$key]) == '护送出关' ){
				$type['hs'] =  6;
				}
				if(trim($saf[$key]) == '帮战' ){
				$type['bz'] = 7;
				}
				if(trim($saf[$key]) == 'BOSS副本' ){
				$type['fb'] = 8;
				}
			}
			$con = implode(",",$type);
			$lin = '{"name":"'.$reason.'","expAdd":'.$add['ex'].',"moneyAdd":'.$add['mon'].',"mfAdd":'.$add['bao'].',"sceneTypes":['.$con.'],"sceneIDs":['.$map.'],"begin":"'.$start.'","end":"'.$end.'"}';
			$url = "http://192.168.0.14:20101/127.0.0.1&ZB&5001&".$lin;
			
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_TIMEOUT,1);
			$rever = curl_exec($ch);
			curl_close($ch);
			 $reg = '/"actID":[0-9]*\,/';//根据返回信息 获取id
			 //$chn = '/"info":\"[u4e00-u9fa5]*\"/';//根据返回信息 获取id
			 preg_match_all($reg , $rever , $out_ary);
			// preg_match_all($chn , $rever , $chn_ary);
			 foreach($out_ary as $key =>$value){
				foreach($value as $k => $v){
					$sta[$k] = strripos($value[$k],":")+1;
					$len[$k]= strripos($value[$k],",") - $sta[$k];
					$sid = substr($value[$k],$sta[$k],$len[$k]);
				}
			}
			if(!empty($sid)){  //提交记录
			$id = $obj->table('ex_double')->insert(array(
					'd_buff' => trim($gold),
					'd_start' => $startdate,
					'd_end'=> $enddate,
					'd_reason'=> $reason,
					'd_day' => date('Y-m-d H:i:s',strtotime("+8 hours")),
					'd_state'=> 1,
					'd_fid' => $ip,
					'd_fname' => '内网14',
					'd_sid' => $sid,
					'd_scene' => $con,
					'd_dou' => $again,
					'd_name' => $this->user['username']
			));
			}
			echo json_encode('success');
		}else{
			echo json_encode('null');
			exit;
		}
		
		
		
		
	}
	/**
	 * 取消
	 */
	public function cancleEx(){
		$id = get_var_value("sid");
		//print_R($id);
		if($id) {
			$obj = D("game_info");
			$state = $obj->table('ex_double')->where(array("d_sid"=>$id))->update(array(
				"d_state" => 0
			));
				$sid = '{"id":"'.$id.'"}';
				$url = "http://192.168.0.14:20101/127.0.0.1&ZB&5002&".$sid;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_TIMEOUT,1);
			$data = curl_exec($ch);
			curl_close($ch);
			
			echo json_encode('success');
		} else {
			echo '1';
		}
	}
	
	/**
	*查记录
	*
	*/
	public function getTable(){
		$total ="";
		$obj = D("game_info");
		$total = $obj->table('ex_double')->field('d_id')->total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		$list = $obj->table('ex_double')->order('d_day desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		// $list = $obj->table('ex_double')->order('d_day desc')->select();
		$state = "";
		if(!empty($list)){
			foreach($list as $key => $value){
				foreach($value as $k => $v){
					if($value['d_state'] == 0){
						$$list[$key]['d_state'] = '撤消';
					}elseif($value['d_state'] == 1){
						$list[$key]['d_state'] = '成功';
					}
				}
			}
			echo json_encode(array('list'=>$list));
		}else{
			echo json_encode('error');
		}
	}
}