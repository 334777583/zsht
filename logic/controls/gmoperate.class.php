<?php
/**
 * FileName: gmoperate.class.php
 * Description:用户管理工具-批量冻结(解冻)账号
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 下午5:06:58
 * Version:1.00
 */
class gmoperate{
	/**
	 * 角色名（多个以逗号分隔）
	 * @var string
	 */
	public $rolename;
	
	/**
	 * 操作原因
	 * @var string
	 */
	public $reason;
	
	/**
	 * 禁言时长
	 * @var int
	 */
	public $stoptime;
	
	/**
	 * 冻结时长
	 * @var int
	 */
	public $freezetime;
	
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
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
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
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00500100", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->gm = new autogm();
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->stoptime = get_var_value("stoptime") == NULL?-1:get_var_value("stoptime");
		$this->freezetime = get_var_value("freezetime") == NULL?-1:get_var_value("freezetime");
		$this->reason = get_var_value("reason") == NULL?"":get_var_value("reason");
		$this->rolename = get_var_value("rolename") == NULL?"":get_var_value("rolename");
		$this->pageSize = get_var_value("pageSize") == NULL?10:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
	}
	
	
	/**
	 * 玩家禁言
	 */
	public function stoptalk(){
	
		$openTime = date("Y-m-d H:i:s");
		$rolename = explode(";",$this->rolename);
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服
		
		global $gm_db;
		$temp = array();
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		// global $t_conf;  //压测服
		// $point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		
		foreach($rolename as $key => $name){
			$arr['cmd'] = 'forbidchat';
			$arr['name'] = $name;
			$arr['time'] = $this->stoptime;
			$temp[$key] = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($arr)),'ServerId'=>$ipList['2'],'stype'=>4,'bHandled'=>0));
		}

		if($this->stoptime > 0){                      			//计算解禁时间
			$openTime = date("Y-m-d H:i:s",time() + $this->stoptime);
		}
		$obj = D("game_base");
		foreach($rolename as $key => $name){
			$id = $obj->table("stop_speak")->insert(array(
					"s_uid"			=>$temp[$key],
					"s_ip"			=>$this->ip,
					"s_role_id"		=>"1",
					"s_role_name"	=>$name,
					"s_status"		=> "1",
					"s_time" 		=> $openTime,
					"s_secends"		=>$this->stoptime,
					"s_reason" 		=> $this->reason,
					"s_operaor" 	=>$this->user["username"],
					"s_callstatus" 	=> 1,
					"s_rolestatus" 	=> 1,
					"s_inserttime" 	=> date("Y-m-d H:i:s")
			
			)); 
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(",",$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	* Description:检查解禁是否处理完成
	* function : check_st
	* Parames : Null
	* Ruturn : Null
	* Author : Kim_drogan SGD
	* Date:2013-7-26 14:05:31
	*/
	private function check_st(){

		$obj = D("game_base");
		$arr = $obj -> field('s_uid') ->table('stop_speak') -> where('s_callstatus=1') -> select();

		if(!empty($arr) && count($arr) > 0){
		
			global $gm_db;

			$str = '';
			foreach($arr as $val){
				$str .= $val['s_uid'].',';
			}
			$str = rtrim($str ,',');
			$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
			$arr_result = $point -> table('php_cmd') -> where('id in ('.$str.') AND stype=4') -> select();
			$point -> table('php_cmd') -> where('id in ('.$str.') AND stype=4 AND bhandled != 0 and phandled = 0') -> update(array('phandled' => 1));
			$fail = array();
			$succ = '';
			if(!empty($arr_result) && count($arr_result) > 0){
				foreach($arr_result as $key => $val){
					if($val['bhandled'] == 2){
						$fail[$key]['id'] = $val['id'];
						$fail[$key]['re'] = $val['cmcmdresult'];
						continue;
					}
					if($val['bhandled'] == 1){
						$succ .= $val['id'].',';
						continue;
					}
					if($val['bhandled'] == 0){
						continue;
					}
				}
				if(!empty($fail) && count($fail) > 0){
					foreach($fail as $val){
						$obj -> table('stop_speak') -> where('s_uid='.$val['id']) ->update(array('s_callstatus'=>0,'s_roleStatus'=>$val['cmcmdresult']));
					}
				}
				if(!empty($succ) && count($succ) > 0){
					$succ = rtrim($succ ,',');
					$obj -> table('stop_speak') -> where('s_uid in ('.$succ.')') ->update(array('s_callstatus'=>2,'s_roleStatus'=>2));
				}
			}
		}else{
			return true;
		}
	}
	
	/**
	* Description:检查冻结是否处理完成
	* function : check_dj
	* Parames : Null
	* Ruturn : Null
	* Author : Kim_drogan SGD
	* Date:2013-7-26 14:05:31
	*/
	private function check_dj(){

		$obj = D("game_base");
		$arr = $obj -> field('f_uid') ->table('freeze') -> where('f_callstatus=1') -> select();
		if(!empty($arr) && count($arr) > 0){
			global $gm_db;
			$str = '';
			foreach($arr as $val){
				$str .= $val['f_uid'].',';
			}
			$str = rtrim($str ,',');
			$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
			$arr_result = $point -> table('php_cmd') -> where('id in ('.$str.') AND stype=1') -> select();
			$point -> table('php_cmd') -> where('id in ('.$str.') AND stype=1 AND bhandled != 0 and phandled = 0') -> update(array('phandled' => 1));

			$fail = array();
			$succ = '';
			if(!empty($arr_result) && count($arr_result) > 0){
				foreach($arr_result as $key => $val){
					if($val['bhandled'] == 2){
						$fail[$key]['id'] = $val['id'];
						$fail[$key]['re'] = $val['cmcmdresult'];
						continue;
					}
					if($val['bhandled'] == 1){
						$succ .= $val['id'].',';
						continue;
					}
					if($val['bhandled'] == 0){
						continue;
					}
				}
				if(!empty($fail) && count($fail) > 0){
					foreach($fail as $val){
						$obj -> table('freeze') -> where('f_uid='.$val['id']) ->update(array('f_callstatus'=>0,'f_roleStatus'=>$val['cmcmdresult']));
					}
				}
				if(!empty($succ) && count($succ) > 0){
					$succ = rtrim($succ ,',');
					$obj -> table('freeze') -> where('f_uid in ('.$succ.')') ->update(array('f_callstatus'=>2,'f_roleStatus'=>2));
				}
			}
		}else{
			return true;
		}
	}

	/**
	* Description:检查下线是否处理完成
	* function : check_xx
	* Parames : Null
	* Ruturn : Null
	* Author : Kim_drogan SGD
	* Date:2013-7-26 15:48:33
	*/
	private function check_xx(){

		$obj = D("game_base");
		$arr = $obj -> field('f_uid') ->table('offline') -> where('f_callstatus=1') -> select();
		if(!empty($arr) && count($arr) > 0){
			global $gm_db;
			$str = '';
			foreach($arr as $val){
				$str .= $val['f_uid'].',';
			}
			$str = rtrim($str ,',');
			$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
			$arr_result = $point -> table('php_cmd') -> where('id in ('.$str.') AND stype=2') -> select();
			$point -> table('php_cmd') -> where('id in ('.$str.') AND stype=2 AND bhandled != 0 and phandled = 0') -> update(array('phandled' => 1));

			$fail = array();
			$succ = '';
			if(!empty($arr_result) && count($arr_result) > 0){
				foreach($arr_result as $key => $val){
					if($val['bhandled'] == 2){
						$fail[$key]['id'] = $val['id'];
						$fail[$key]['re'] = $val['cmcmdresult'];
						continue;
					}
					if($val['bhandled'] == 1){
						$succ .= $val['id'].',';
						continue;
					}
					if($val['bhandled'] == 0){
						continue;
					}
				}
				if(!empty($fail) && count($fail) > 0){
					foreach($fail as $val){
						$obj -> table('offline') -> where('f_uid='.$val['id']) ->update(array('f_callstatus'=>0,'f_roleStatus'=>$val['cmcmdresult']));
					}
				}
				if(!empty($succ) && count($succ) > 0){
					$succ = rtrim($succ ,',');
					$obj -> table('offline') -> where('f_uid in ('.$succ.')') ->update(array('f_callstatus'=>2,'f_roleStatus'=>2));
				}
			}
		}else{
			return true;
		}
	}
	
	/**
	* Description:获取禁止的列表数据
	* function : getStopInfo
	* Parames : Null
	* Ruturn : Null
	* Author : Kim_drogan SGD
	* Date:2013-7-26 14:05:31
	*/
	public function getStopInfo(){
		$this -> check_st();
		$total = 0;							//记录总数
		$obj = D("game_base");
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$total = $obj -> table("stop_speak") -> where("s_ip = ".$this->ip) -> order("s_inserttime desc") -> total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"formAjax","go","page");
		$pageHtml = $page->getPageHtml();
		$list = $obj->table("stop_speak")->where("s_ip = ".$this->ip)->order("s_inserttime desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$result = array(
				"list"=>$list,
				"pageHtml"=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	} 
	
	
	/**
	* Description:解禁
	* function : allowtalk
	* Parames : Null
	* Ruturn : Null
	* Author : Kim_drogan SGD
	* Date:2013-7-26 14:05:31
	*/
	public function allowtalk(){
		
		$openTime = date("Y-m-d H:i:s");
		$rolename = explode(';',$this->rolename);
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服

		global $gm_db;
		$temp = array();
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		// global $t_conf;  //压测服
		// $point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		
		foreach($rolename as $key => $name){
			$arr['cmd'] = 'forbidchat';
			$arr['name'] = $name;
			$arr['time'] = 0;
			$temp[$key] = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($arr)),'ServerId'=>$ipList['2'],'stype'=>4,'bHandled'=>0));
		}

		$obj = D('game_base');
		foreach($rolename as $key => $name){
			$id = $obj->table("stop_speak")->insert(array(
					"s_uid"			=>$temp[$key],
					"s_ip"			=>$this->ip,
					"s_role_id"		=>"1",
					"s_role_name"	=>$name,
					"s_status"		=> 2,
					"s_time" 		=> $openTime,
					"s_secends"		=> 0,
					"s_reason" 		=> $this->reason,
					"s_operaor" 	=>$this->user["username"],
					"s_callstatus" 	=> 1,
					"s_rolestatus" 	=> 1,
					"s_inserttime" 	=> date("Y-m-d H:i:s")
			
			)); 
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(',',$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	* Description : 冻结
	* function : freeze
	* Parames : Null
	* Ruturn : Null
	* Author : Kim_drogan SGD
	* Date : 2013-7-26 14:05:31
	*/
	public function freeze(){
	
		$openTime = date("Y-m-d H:i:s");
		$rolename = explode(';',$this->rolename);
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服

		global $gm_db;
		$temp = array();
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		// global $t_conf;
		// $point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		if(!$point){
			echo json_encode(array(
				'error' => '数据库连接失败！'
			));
			exit;
		}
		
		foreach($rolename as $key => $name){
			$arr['cmd'] = 'forbidlogin';
			$arr['name'] = $name;
			$arr['time'] = $this->freezetime;
			$temp[$key] = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($arr)),'ServerId'=>$ipList['2'],'stype'=>1,'bHandled'=>0));
		}
		$openTime = date("Y-m-d H:i:s");
		if($this->freezetime > 0){                      //计算解冻时间
			$openTime = date("Y-m-d H:i:s",time() + $this->freezetime);
		}
		$obj = D('game_base');
		foreach($rolename as $key => $name){
			$id = $obj->table("freeze")->insert(array(
				"f_uid"	=>$temp[$key],
				"f_ip"=>$this->ip,
				"f_role_id"=>'1',
				"f_role_name"=> $name,
				"f_status"=> '1',
				"f_time" => $openTime,
				"f_secends"=>$this->freezetime,
				"f_reason" => $this->reason,
				"f_operaor" =>$this->user["username"],
				"f_callstatus" => 1,
				"f_rolestatus" => 1,
				"f_inserttime" => date("Y-m-d H:i:m")			
			));
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(',',$ids));
		echo json_encode($com);
		exit;	
	}
	
	/**
	 * 玩家解冻
	 */
	public function unfreeze(){
		$openTime = date("Y-m-d H:i:s");
		$rolename = explode(';',$this->rolename);
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服

		global $gm_db;
		$temp = array();
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		// global $t_conf;  //压测服
		// $point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		if(!$point){
			echo json_encode(array(
				'error' => '数据库连接失败！'
			));
			exit;
		}
		
		foreach($rolename as $key => $name){
			$arr['cmd'] = 'forbidlogin';
			$arr['name'] = $name;
			$arr['time'] = 0;
			$temp[$key] = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($arr)),'ServerId'=>$ipList['2'],'stype'=>1,'bHandled'=>0));
		print_R($point);
		}
		
		$obj = D('game_base');
		foreach($rolename as $key => $name){
			$id = $obj->table("freeze")->insert(array(
				"f_uid"	=>$temp[$key],
				"f_ip"=>$this->ip,
				"f_role_id"=>'1',
				"f_role_name"=> $name,
				"f_status"=> '2',
				"f_time" => 0,
				"f_secends"=>$this->freezetime,
				"f_reason" => $this->reason,
				"f_operaor" =>$this->user["username"],
				"f_callstatus" => 1,
				"f_rolestatus" => 1,
				"f_inserttime" => date("Y-m-d H:i:m")			
			));
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(',',$ids));
		echo json_encode($com);
		exit;	
	}
	
	/**
	 * 获取冻结操作数据库信息
	 */
	public function getFreezeInfo(){
		$this->check_dj();
		$total = 0;//记录总数
		$obj = D("game_base");
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$total = $obj->table("freeze")-> where("f_ip = ".$this->ip) -> order("f_inserttime desc")->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"freezeAjax","fgo","fpage");
		$pageHtml = $page->getPageHtml();
		$list = $obj->table("freeze")-> where("f_ip = ".$this->ip) ->order("f_inserttime desc,f_id desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$result = array(
				"list"=>$list,
				"pageHtml"=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 强制下线
	 */
	public function offline(){

		$rolename = explode(';',$this->rolename);
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服

		global $gm_db;
		$temp = array();
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		// global $t_conf;  //压测服
		// $point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		
		foreach($rolename as $key => $name){
			$arr['cmd'] = 'kickplayer';
			$arr['name'] = $name;
			$arr['info'] = $this->reason;
			$temp[$key] = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($arr)),'ServerId'=>$ipList['2'],'stype'=>2,'bHandled'=>0));
		}

		$obj = D('game_base');
		foreach($rolename as $key => $name){
			$id = $obj->table("offline")->insert(array(
					'f_uid' 		=> $temp[$key],
					"f_ip"			=> $this->ip,
					"f_role_id"		=> "1",
					"f_role_name"	=> $name,
					"f_status"		=> "1",
					"f_time" 		=> date("Y-m-d H:i:s"),
					"f_reason" 		=> $this->reason,
					"f_operaor" 	=> $this->user["username"],
					"f_callstatus" 	=> 1,
					"f_rolestatus" 	=> 1,
					"f_inserttime" 	=> date("Y-m-d H:i:m")
								
			));
			if($id != false){
				$ids[] = $id;
			}
		}
		$com = array("ids"=>implode(',',$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 获取下线操作数据库信息
	 */
	public function getOfflineInfo(){
		$this -> check_xx();
		$total = 0;							//记录总数
		$obj = D("game_base");
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$total = $obj->table("offline")-> where("f_ip = ".$this->ip)-> order("f_inserttime desc")->total();
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,"offAjax","ogo","opage");
		$pageHtml = $page->getPageHtml();
		$list = $obj->table("offline")-> where("f_ip = ".$this->ip)->order("f_inserttime desc,f_id desc")->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$result = array(
				"list"=>$list,
				"pageHtml"=>$pageHtml,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
}