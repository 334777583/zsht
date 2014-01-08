<?php
/**
 * FileName: gmtoolspass.class.php
 * Description:用户管理工具(GM)-道具申请审批
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */
class gmtoolspass{
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	
	/**
	 * gm接口类
	 * @var class
	 */
	private $gm;
	
	/**
	 * 开始时间
	 * @var sting
	 */
	private $startdate;
	
	/**
	 * 结束时间
	 * @var string
	 */	
	private $enddate;
	
	
	/**
	 * 主键ID
	 * @var int
	 */
	private $id;
	
	/**
	 * 服务器IP
	 * @var string
	 */
	private $ip;
	
	/**
	 * 道具列表
	 * @var array
	 */
	private $toolList = array();
	
	/**
	 * 角色列表
	 * @var string
	 */
	private $roleName;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400700', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->id =  get_var_value('id') == NULL? -1 : get_var_value('id');
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->toolList = get_var_value('toolList') == NULL? array(): get_var_value('toolList');
		$this->startdate = get_var_value('startdate') == NULL? '': get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->roleName =  get_var_value('roleName') == NULL? '' : get_var_value('roleName');
	}
	
	/**
	 * 获取道具申请
	 */
	public function getPassTable(){
		$total = 0;							//记录总数
		$stype = get_var_value('stype');	//审核状态
		
		$obj = D('game_info');

		if(empty($this->startdate) && empty($this->enddate)){
			if($stype == 10) {
				$total = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status != -1')->total();
			} else {
				$total = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status = '.$stype)->total();
			}
		}else{
			if($this->startdate == $this->enddate){
				if($stype == 10) {
					$total = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%','t_ip' => $this->ip, 't_status !=' => '-1'))->total();
				} else {
					$total = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%','t_ip' => $this->ip, 't_status =' => $stype))->total();
				}
			}else{
				if($stype == 10) {
					$total = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status !=' => '-1'))->total();
				} else {
					$total = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status =' => $stype))->total();
				}	
			}
		}
		
		$ipList = autoConfig::getIPS();		//获取服务器信息		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();


		
			if(empty($this->startdate) && empty($this->enddate)){
				if($stype == 10) {
					$list = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status != -1')->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				}elseif ($stype < 2) {
					$list = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status=1 or t_status=0')->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				} else {
					$list = $obj->table('tools_ask')->where('t_ip ='.$this->ip .' and t_status = '.$stype)->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
				}	
			}else{
				if($this->startdate == $this->enddate){
					if($stype == 10) {
						$list = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%', 't_ip' => $this->ip, 't_status !=' => '-1'))->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
					}elseif ($stype < 2) {
						$list = $obj->table('tools_ask')->where("t_inserttime like '{$this->startdate}' and t_ip={$this->ip} and t_status < 2")->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
					} else {
						$list = $obj->table('tools_ask')->where(array('t_inserttime like' => $this->startdate.'%', 't_ip' => $this->ip, 't_status =' => $stype))->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
					}	
				}else{
					if($stype == 10) {
						$list = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status !=' => '-1'))->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
					}elseif ($stype < 2) {
						$list = $obj->table('tools_ask')->where("t_inserttime >= '{$this->startdate}' and t_inserttime <= '".date("Y-m-d",strtotime($this->enddate)+86400)."' and t_ip={$this->ip} and t_status < 2")->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
					} else {
						$list = $obj->table('tools_ask')->where(array('t_inserttime >= ' => $this->startdate,'t_inserttime < ' => date("Y-m-d",strtotime($this->enddate)+86400), 't_ip' => $this->ip, 't_status =' => $stype))->order('t_status asc, t_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
					}
				}
			}

		$result = array(
				'list'=>$list,
				'pageHtml'=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 审核通过(0:未审核； 1：申请中；2：申请不通过；3：已通过但发送失败；4：已通过但发送成功)
	 */
	public function changeStatus(){
		$obj = D("game_info");
		
		$point = D('troh_game');

		$bo = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->find();
		// if(isset($bo['t_status'])  && $bo['t_status'] != 3) {	//如果多人编辑时，该记录已经被修改过了，直接查询返回
		// 	$result = array(
		// 			'id' => $this->id,
		// 			'status' => $bo['t_status'],
		// 			'auditor' => $bo['t_auditor']
		// 		);
		// 	echo json_encode($result);
		// 	exit;
		// }
		
		$ip = "";			//服务器IP
		$loginName = "";	//当前登录用户
		$port = 0;			//端口
		$server = $obj->table("servers")->where(array("s_id"=>$this->ip))->find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
	
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		
		$moneyList = array();			//金钱列表
		$toolList = array();			//道具列表
		
		$ask = $obj->table('tools_ask')->where('t_id = '. $this->id)->find();
		$list = $obj->table('tools_list')->where('t_ta_id = '.$this->id)->select();
		if(is_array($list)){
			$toolList = $list;
		}
		if(is_array($ask)){
			$Gold = $ask;
			$moneyList = $ask;
			$moneyList['t_gold'] = 0;
		}

		$info =$info1 = array();				//发送json数据
		
		$status = 4;					//邮件发送状态，默认为失败
		if($this->roleName == '全服'){	//全服邮件发送
			$info['receiverName'] = 0;
		}else{							//单人发邮件发送
			$info['receiverName'] = $this->roleName;
		}
		$info['sender'] = '系统管理员';
		$info['title'] = $moneyList['t_title'];
		$info['content'] = $moneyList['t_content'];
		$info['items'] =  array();
		$monArr = array();				//金钱类型：数量   1 铜币, 2 银子, 3 元宝, 4 绑定装备
		$toolArr = array();				//道具列表
		if(!empty($moneyList)){
			$monArr = array(
					'1' => $moneyList['t_copper'],
					//'2' => $moneyList['t_silver'],
					'3'	=> $moneyList['t_gold'],
					'4' => $moneyList['t_bgold']
				);
		}

		if(!empty($toolList)){
			foreach($toolList as $tool){
				switch($tool['t_bstatus']){
					case '1':$tool['t_bstatus'] = 1;break;
					case '2':$tool['t_bstatus'] = 0;break;
					default :$tool['t_bstatus'] = 0;
				}
				$itemArr = array(
					'itemId' =>	$tool['t_tid'],				//道具的配置表ID
					"count"	=> $tool['t_num'],				//发送道具的数量
					"bdstate" => $tool['t_bstatus'],		//绑定状态	
					"strengLv" => $tool['t_level'],			//装备的话，强化等级
					"addiLv" =>	$tool['t_add']				//装备的话,追加等级
				);
				$toolArr[] = $itemArr;
			}
		}
		$info['items'] = $toolArr;
		$info['moneys'] = $monArr;;

		$One = $obj->fquery("SELECT t_auditor,t_prower,t_status FROM tools_ask WHERE t_id={$this->id}");	//获取当前数据信息
		
		if (empty($One[0]['t_auditor'])) {
			$username = $this->user['username'].'已审核通过';			
			$result = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->update("t_prower=t_prower+1,t_status=1,t_auditor='{$username}',t_audittime=DATE_FORMAT(NOW(),'%Y-%m-%d')");
		}elseif(!empty($One[0]['t_auditor'])){
			
			if (!in_array($this->user["username"].'已审核通过', explode(',', $One[0]['t_auditor']))) {
				$username_arr = array($One[0]['t_auditor'],$this->user["username"].'已审核通过');						//合并每次审核人员的名字
				$username = implode(',', $username_arr);
				$result = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->update("t_prower=t_prower+1,t_status=1,t_auditor='{$username}',t_audittime=DATE_FORMAT(NOW(),'%Y-%m-%d')");
			}
		}

		$Twe = $obj->fquery("SELECT t_prower,t_auditor,t_status FROM tools_ask WHERE t_id={$this->id}");
			error_reporting(0);
		
		if ($Twe[0]['t_prower'] > 3 && $Twe[0]['t_prower'] < 5 && $Twe[0]['t_status'] != 4 && $Twe[0]['t_status'] != 2 && (!in_array($this->user["username"].'已审核通过', explode(',', $One[0]['t_auditor'])))) {	//	判断是否四个人都通过，
			
			
			if($this->roleName == '全服'){	
												//全服邮件发送
				//新加根据等级发送
				$info['minLv'] = $moneyList['t_minlv'];
				$info['maxLv'] = $moneyList['t_maxlv'];
				$info['endTime'] = $moneyList['t_endtime'];

				$callReasult = $this->gm->gm3002($info,$ip,$port,$loginName);	//调用gm接口
				
				if($callReasult == "error"){
					sleep(1);
					$callReasult = $this->gm->gm3002($info,$ip,$port,$loginName);	
					if($callReasult == "error"){
						echo json_encode('error');
						exit;
					}
				}
			}else{
				
				if ($Gold['t_gold'] > 0) {
					$goldResult = $this->gm->gm4001($this->roleName,$Gold['t_gold'],$Gold['t_ip']);//调用充值接口
				}
				if($Gold['t_bgold'] > 0 || $Gold['t_copper'] > 0 || !empty($list)){
					$callReasult = $this->gm->gm3001($info,$ip,$port,$loginName);	//调用gm接口
				}

				
				if($callReasult == "error"){
					sleep(1);
					$callReasult = $this->gm->gm3001($info,$ip,$port,$loginName);	
					if($callReasult == "error"){
						echo json_encode('error');
						exit;
					}
				}
			}
			if($callReasult == "error"){
				echo json_encode('error');
				exit;
			}
					
			$arr = explode('|',$callReasult);

			if(isset($arr[1])){
				$resultList = json_decode($arr[1],true);
				if(isset($resultList) && isset($resultList['code'])){
					if($resultList['code'] == 0){
						$status = 4;	//2:已通过并发送成功
					}elseif($resultList['code'] == 1){
						$status = 3;	//4:已通过但发送失败
					}else{
						$status = 1;
					}
				}
			}
			
			$state = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->update(array(
					"t_status" => $status,
					//"t_auditor" => $this->user["username"],
					"t_audittime" => date("Y-m-d H:i:s")
			));
		}else{
			$status = 1;
		}

		
		
		if($result !=  false){
			$result1 = array(
					'id' => $this->id,
					'status' => $status,
					'auditor' => $Twe[0]["t_auditor"]
				);
			echo json_encode($result1);
			exit;
		}else{
			echo json_encode("error");
			exit;
		}

	}
	
	/**
	 * 审核不通过(1：申请中；2：申请不通过；3：已通过但发送失败；4：已通过但发送成功)
	 */
	public function nopass(){
		$obj = D('game_info');
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$One = $obj->fquery("SELECT t_auditor,t_prower,t_status FROM tools_ask WHERE t_id={$this->id}");	//获取当前数据信息
		$username = '';
		if (empty($One[0]['t_auditor'])) {
			$username = $this->user["username"].'不通过';
		}else{
			$username_arr = array($One[0]['t_auditor'],$this->user["username"].'不通过');								//合并每次审核人员的名字
			$username = implode(',', $username_arr);
		}
		//$result = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->update("t_prower=t_prower+1,t_status=1,t_auditor='{$username}',t_audittime=DATE_FORMAT(NOW(),'%Y-%m-%d')");
		
		//$result = $obj->fquery("DELETE FROM tools_ask WHERE id={$this->id}");//删除否认的需求
		
		$state = $obj->table('tools_ask')->where(array("t_id"=>$this->id))->update(array(
				"t_status" => 2,
				"t_auditor" => $username,
				"t_audittime" => date("Y-m-d H:i:s")
		));
		$Twe = $obj->fquery("SELECT t_prower,t_auditor,t_status FROM tools_ask WHERE t_id={$this->id}");
		if($state !=  false){
			$result = array(
					'id' => $this->id,
					'status' => 2,
					'auditor' => $Twe[0]["t_auditor"]
				);
			echo json_encode($result);
			
			exit;
		}else{
			echo json_encode("error");
			exit;
		}
	}
}