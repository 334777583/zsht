<?php
/**
 * FileName: gmemail.class.php
 * Description:用户管理工具-邮件
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午3:40:29
 * Version:1.00
 */
class gmemail{
	/**
	 * 角色名（多个以逗号分隔）
	 * @var string
	 */
	public $rolename;
	
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
	 * 标题
	 * @var string
	 */
	public $title;
	
	
	/**
	 * 信件内容
	 * @var string
	 */
	public $content;
	
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
	 * 发送类型(1:角色名；2：全服)
	 * @var int
	 */
	private $srole;
	
	
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00500300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->reason = get_var_value('reason') == NULL? '': get_var_value('reason');
		$this->rolename = get_var_value('rolename') == NULL? '' : get_var_value('rolename');
		$this->title = get_var_value('title') == NULL? '' : get_var_value('title');
		$this->content = get_var_value('content') == NULL? '': $_POST['content'];
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->srole =  get_var_value('srole') == NULL? 0 : get_var_value('srole');
	}
	
	/**
	 * 发送邮件
	 */
	public function sendEmail(){
		$obj = D("game_base");
		
		$ipList = autoConfig::getConfig($this->ip);	//设置C++服务器对应的服
		
		global $gm_db;
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		
		$ids = array();						 //保存返回的插入id
		if(empty($this->title)){
			$this->title = '系统邮件';
		}

		if('1' == $this->srole){			//单人发邮件发送
			if(!empty($this->rolename)){ 	//角色名不为空

				$rolename = explode(';',$this->rolename);
				foreach($rolename as $name){
					$info['cmd'] = 'sendmail';
					$info['name'] = $name;
					$info['title'] = $this->title;
					$info['type'] = 1;
					$info['content'] = $this->content;
					
					$uid = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($info)),'ServerId'=>$ipList['2'],'stype'=>3,'bHandled'=>0));
					
					$id = $obj->table('email')->insert(array(
							'e_ip' => $this->ip,
							'e_uid' => $uid,
							'e_name' => $name,
							'e_time'=> date('Y-m-d H:i:s'),
							'e_reason'=> $this->reason,
							'e_title' => $this->title,
							'e_content'=> $this->content,
							'e_status' => -1,
							'e_operaor' => $this->user['username'],
					));
					
					
					
					if($id != false){
						$ids[] = $id;
					}
				}
				
				$result = array('ids'=>implode(',',$ids));
				
			}else{  					//角色名为空
				$result = array('error' => '请输入角色名！');
			}
		} else if('2' == $this->srole){	//全服邮件
			$info['cmd'] = 'sendmail';
			$info['name'] = '全服';
			$info['title'] = $this->title;
			$info['type'] = 2;
			$info['content'] = $this->content;
			
			$uid = $point -> table('php_cmd') -> insert(array('GmCmd'=>addslashes(myjson($info)),'ServerId'=>$ipList['2'],'stype'=>3,'bHandled'=>0));
			
			$id = $obj->table('email')->insert(array(
							'e_ip' => $this->ip,
							'e_uid' => $uid,
							'e_name' => '全服',
							'e_time'=> date('Y-m-d H:i:s'),
							'e_reason'=> $this->reason,
							'e_title' => $this->title,
							'e_content'=> $this->content,
							'e_status' => -1,
							'e_operaor' => $this->user['username'],
					));
					
			
			
			if($id != false){
				$ids[] = $id;
			}
		
					
			if($id != false){
				$ids[] = $id;
			}
			
			$result = array('ids'=>implode(',',$ids));
			
		}

		$result = array('ids'=>implode(',',$ids));
		echo json_encode($result);
		exit;	
		
	}


	/**
	* Description:检查邮件发送是否处理完成
	* function : check_yj
	* Parames : Null
	* Ruturn : Null
	* Author : xiaochengcheng
	* Date:2013-8-1 16:12:10
	*/
	private function check_yj(){
		$obj = D("game_base");
		$arr = $obj ->table('email') -> field('e_uid')  -> where('e_status=-1') -> select();

		if(!empty($arr) && count($arr) > 0){
		
			global $gm_db;

			$str = '';
			foreach($arr as $val){
				$str .= $val['e_uid'].',';
			}
			$str = rtrim($str ,',');
			$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
			$arr_result = $point -> table('php_cmd') -> where('id in ('.$str.') AND stype=3') -> select();
			$point -> table('php_cmd') -> where('id in ('.$str.') AND stype=3 AND bhandled != 0 and phandled = 0') -> update(array('phandled' => 1));

			$fail = array();
			$succ = '';
			if(!empty($arr_result) && count($arr_result) > 0){
				foreach($arr_result as $val){
					if($val['bhandled'] == 2){
						$fail[]['id'] = $val['id'];
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
						@$obj -> table('email') -> where("e_uid = ".$val['id']) ->update(array('e_status' => 2, "e_time" => date("Y-m-d H:i:s")));
					}
				}
				if(!empty($succ) && count($succ) > 0){
					$succ = rtrim($succ ,',');
					@$obj -> table('email') -> where("e_uid in (".$succ.")") ->update(array('e_status' => 1, "e_time" => date("Y-m-d H:i:s")));
				}
			}
		}else{
			return true;
		}
	}	
	
	/**
	 * 获取邮件数据库记录
	 */
	public function getEmailTable(){
		$this->check_yj();
	
		$total = 0;//记录总数
		$obj = D('game_base');
		$total = $obj-> table('email') -> where('e_ip = '.$this->ip) -> total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		$list = $obj->table('email')-> where('e_ip = '.$this->ip) -> order('e_time desc')->limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize))->select();
		foreach($list as $k => $v){
			$list[$k]['e_content'] = htmlspecialchars_decode($v['e_content']);
		}
		$result = array(
				'list'=>$list,
				'pageHtml'=>$pageHtml,
				'ipList' => $ipList,
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 获取全部群邮件
	 */
	public function getEmails() {
		if(!in_array('00401000', $this->user['code'])){
			echo 'not available!';
			exit();
		}	
		
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		$info = array();
		$result = array();
		$callReasult = $this->gm->gm3003($info,$ip,$port,$loginName);
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm3003($info,$ip,$port,$loginName);
			if($callReasult == "error"){
				$result = array('error'=>'远程超时无响应！');
				echo json_encode($result);
				exit;
			}
		}
		
		$arr = explode('|',$callReasult);
		$data = array();
		
		if(isset($arr[1])) {
			$resultList = json_decode($arr[1],true);
			if(isset($resultList['mails'])) {
				$mails = $resultList['mails'];
				
				foreach($mails as $mail) {
					$tmp = array();
					$tmp['id'] = $mail['massId'];
					$tmp['type'] = '全服';
					$tmp['createTime'] = date('Y-m-d H:i:s', substr($mail['createTime'], 0, 10));
					$tmp['endTime'] = date('Y-m-d H:i:s', substr($mail['createTime']+$mail['endTime'], 0, 10));
					$tmp['title'] = $mail['title'];
					$tmp['content'] = $mail['content'];
					if($mail['minLv'] == '0') {
						$mail['minLv'] = '无限制';
					}
					if($mail['maxLv'] == '0') {
						$mail['maxLv'] = '无限制';
					}
					$tmp['minLv'] = $mail['minLv'];
					$tmp['maxLv'] = $mail['maxLv'];
					$data[] = $tmp;
				}
			}
		}
		
		$result = array('result' => $data);
		echo json_encode($result);
		exit;
	}
	
	
	/**
	 * 删除指定ID的群邮件
	 */
	public function delEmail() {
		$id = get_var_value('id');
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		if($id) {
			$result = array();
			$callReasult = $this->gm->gm3004($id, $ip, $port, $loginName);
			if($callReasult == "error"){
				sleep(1);
				$callReasult = $this->gm->gm3004($info,$ip,$port,$loginName);
				if($callReasult == "error"){
					$result = array('error'=>'远程超时无响应！');
					echo json_encode($result);
					exit;
				}
			}
			
			$arr = explode('|',$callReasult);
			if(isset($arr[1])) {
				$resultList = json_decode($arr[1],true);
				if(isset($resultList) && isset($resultList['code'])){
					if($resultList['code'] == 0){
						$status = 'success';
					}else{
						$status = 'error';
					}
				}
			}
			echo json_encode($status);
			exit;
		} else {
			echo '1';
		}	
	}	
}