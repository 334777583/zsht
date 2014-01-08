<?php
/**
 * FileName: gmstart.class.php
 * Description:热启动
 * Author: jan
 * Date:2013-11-11 下午4:35:38
 * Version:1.00
 */
class gmstart{
	
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
			if(!in_array("00500900", $this->user["code"])){
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
	 * 预约启动
	 */
	public function hotstart(){
		list($ip, $port, $loginName) = autoConfig::getServer($this->ip);
		$startdate = get_var_value('startdate')== NULL? date("Y-m-d H:i:s"):get_var_value('startdate');
		$ip = get_var_value('sip');
		$reson = get_var_value('reson');
		$sys = get_var_value('sys');
		$obj = D('game_info');
		$start = date('Ymd His',strtotime($startdate)) ;
		if(!empty($sys)){
			foreach($sys as $key => $value){
				foreach($value as $k => $v){
					if(in_array('ka',$value)){
						$sys[$key]['isXml'] = 'true';
					}else{
						$sys[$key]['isXml'] = 'false';
					}
					if(in_array('co',$value)){
						$sys[$key]['isScript'] = 'true';
					}else{
						$sys[$key]['isScript'] = 'false';
					}
					$buff[$key] = $sys[$key]['code'];
				}
				$sy[] = $sys[$key];
			}
			// foreach($sy as $key =>$value){
				// $sy
			// }
		}else{
			exit;
		}
		$info =array('list'=>$sy);
		//提交记录
		
		$obj->table('hot_start')->insert(array(
			'd_buff' => implode(",",$buff),
			'd_start' => $startdate,
			'd_reason'=> $reson,
			'd_day' => date('Y-m-d H:i:s',strtotime("+8 hours")),
			'd_state'=> 1,
			'd_fid' => $ip,
			'd_fname' => '内网14',
			'd_name' => $this->user['username']
		));
		
		
		$callReasult = $this->gm->gm6001($info,$ip,$port,$loginName);	//调用gm接口
		if($callReasult == 'error'){
			sleep(1);
			$callReasult = $this->gm->gm6001($info,$ip,$port,$loginName);
			if($callReasult == 'error') {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		$arr = explode('|', $callReasult);
		if(isset($arr[1])){
			$result = json_decode($arr[1],true);
			if(isset($result['errorCode'])){
				if($result['errorCode'] == 0){		//成功
					$obj -> table('hot_start') -> where('d_id = '.$this->gid) -> update('d_state = 2');
					//header('Location: http://192.168.0.148/1stat/brophp/index.php/gmstart/show');
				}	
			}
			
		}
		echo json_encode('success');
		
	}
	/**
	 * 取消
	 */
	public function cancleEx(){
		$id = get_var_value("sid");
		//print_R($id);
		if($id) {
			$obj = D("game_info");
			$state = $obj->table('hot_start')->where(array("d_sid"=>$id))->update(array(
				"d_state" => 0
			));
				$sid = '{"id":"'.$id.'"}';
				$url = "http://192.168.0.14:20101/127.0.0.1&ZB&5002&".$sid;
			//print_r($url);	
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
		$total = $obj->table('hot_start')->field('d_id')->total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		$list = $obj->table('hot_start')->order('d_day desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		// $list = $obj->table('ex_double')->order('d_day desc')->select();
		$state = "";
		if(!empty($list)){
			foreach($list as $key => $value){
				foreach($value as $k => $v){
					if($value['d_state'] == 0){
						$value['d_state']= '撤消';
					}elseif(
						$value['d_state'] == 1){
						$value['d_state'] = '提交';
					}elseif(
						$value['d_state'] == 2){
						$value['d_state'] = '成功';
					}
				}
			}
			echo json_encode(array('list'=>$list));
		}else{
			echo json_encode('error');
		}
	}
}