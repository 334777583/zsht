<?php
/**
 * FileName: gmnew.class.php
 * Description:用户管理工具-公告管理
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午5:49:26
 * Version:1.00
 */
class gmnew{
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
	 * 消息内容
	 * @var string
	 */
	private $content;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $starttime;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $endtime;
	
	/**
	 * 消息类型
	 * @var int
	 */
	private $type;
	
	/**
	 * 时间间隔
	 * @var int
	 */
	private $interval;
	
	/**
	 * 滚动公告ID
	 * @var int
	 */
	private $gid;
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00500200', $this->user['code'])){
				echo 'not available!';
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
	 * 发布公告
	 */
	public function sendNew(){
		list($ip, $port, $loginName) = autoConfig::getServer($this->ip);

		$obj = D('game_info');
		$end = '';
		$start = '';
		if(!empty($this->endtime)){
			$end = date('Ymd His',strtotime($this->endtime));
		}
		if(!empty($this->starttime)){
			$start = date('Ymd His',strtotime($this->starttime));
		}
		
		//查找滚动公告最大ID，没有默认1;
		$currBo = $obj -> table('current_news') -> field('max(c_id) as gid') -> find();
		
		if(isset($currBo['gid'])){
			$gid = $currBo['gid'] + 1;
		}else{
			$gid = 1;
		}
		
		$info = array();
		$info['endTime'] = $end;
		$info['gapTime'] = $this->interval;
		$info['message'] = str_replace('&', '%26', $this->content);			//处理带&符号的链接
		$info['startTime'] = $start;
		$info['id'] = $gid;
		
		$callReasult = $this->gm->gm1002($info,$ip,$port,$loginName);	//调用gm接口
		if($callReasult == 'error'){
			sleep(1);
			$callReasult = $this->gm->gm1002($info,$ip,$port,$loginName);
			if($callReasult == 'error') {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$result = array();
		$arr = explode('|',$callReasult);
		if(isset($arr[1])){
			$result = json_decode($arr[1],true);
		}
	
		$search = array("'<script[^>]*?>.*?</script>'si");	//过滤特殊字符
		$replace = array("");
		$this->content = trim(addslashes(nl2br(stripslashes(preg_replace($search, $replace, $this->content)))));
		$this->content = htmlspecialchars($this->content);	
		if($result['result'] == 0){							//滚动公告请求成功，存入当前滚动数据库(current_news)
			if($this->starttime != ''){
				$obj->table('current_news')->insert(array(
					'c_ip'=>$this->ip,
					'c_starttime'=>$this->starttime,
					'c_endtime'=> $this->endtime,
					'c_interval' => $this->interval,
					'c_content' => $this->content,
					'c_date' => date('Y-m-d H:i:s'),
					'c_operaor' => $this->user['username'],
					'c_inserttime' => date('Y-m-d H:i:s')
				));
			}
			
			$rstatus = 1;
		}else if($result['result'] == 1){
			$rstatus = 2;
		}
	
		if($this->starttime == ''){
			$this->endtime = $this->starttime = date('Y-m-d H:i:s');
		}

		$id = $obj->table('news')->insert(array(
				'n_ip'=>$this->ip,
				'n_status'=>$this->type,
				'n_starttime'=>$this->starttime,
				'n_endtime'=> $this->endtime,
				'n_interval' => $this->interval,
				'n_content' => $this->content,
				'n_date' => date('Y-m-d H:i:s'),
				'n_operaor' => $this->user['username'],
				'n_callstatus' => $rstatus,
				'n_inserttime' => date('Y-m-d H:i:s')
	
		));
		
		$ids = array();//保存当前操作数据库返回的记录id
		if($id != false){
			$ids[] = $id;
		}
		
		$com = array('ids'=>implode(',',$ids));
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 获取公告操作数据库信息
	 */
	public function getNewInfo(){
		$total = 0;//记录总数
		$obj = D('game_info');
		$total = $obj -> table('news') -> where("n_ip = ".$this->ip ." and n_status = ".$this->type) -> order('n_inserttime desc') -> total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'newAjax','ngo','npage');
		$pageHtml = $page -> getPageHtml();
		$list = $obj->table('news')-> where("n_ip = ".$this->ip." and n_status = ".$this->type)->order('n_inserttime desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		foreach($list as $k => $v){
			$list[$k]['n_content'] = htmlspecialchars_decode($v['n_content']);
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
	 * 获取当前滚动公告
	 */
	public function getCurInfo(){
		$obj = D('game_info');
		$list = $obj -> table('current_news') 
					 -> where(array(
								'c_endtime >=' => date('Y-m-d H:i:s'),
								'c_isdel' => 0,
								'c_ip' => $this->ip
							))
					 -> select();
					 
		foreach($list as $k => $v){
			$list[$k]['c_content'] = htmlspecialchars_decode($v['c_content']);
		}			 
					 		 		 			 		 
		$ipList = autoConfig::getIPS();		//获取服务器信息
		$result = array(
				'list'=>$list,
				'ipList' => $ipList
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 删除滚动公告
	 */
	public function deleteNew(){
		list($ip, $port, $loginName) = autoConfig::getServer($this->ip);
		
		$obj = D('game_info');
		$info = array();
		$info['ids'] = array($this->gid);	
		$callReasult = $this -> gm -> gm1007($info,$ip,$port,$loginName);	//调用gm接口
		if($callReasult == 'error'){
			sleep(1);
			$callReasult = $this -> gm -> gm1007($info,$ip,$port,$loginName);
			if($callReasult == 'error') {
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
		}
		
		$arr = explode('|', $callReasult);
		if(isset($arr[1])){
			$result = json_decode($arr[1],true);
			if(isset($result['result'])){
				if($result['result'] == 0){		//成功
					$obj -> table('current_news') -> where('c_id = '.$this->gid) -> update('c_isdel = 1');
				}	
			}
		}
			
		echo json_encode('success');
		exit;
	}
}