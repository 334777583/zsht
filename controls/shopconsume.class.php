<?php
/**
 * FileName: shopconsume.class.php
 * Description:商城消费记录
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午3:40:29
 * Version:1.00
 */
class shopconsume{
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
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 账号类型
	 * @var int
	 */
	private $type;
	
	/**
	 * 搜索内容
	 * @var string
	 */
	private $key;
	
	/**
	 * 模糊查询(0：模糊；1：精确)
	 * @var int
	 */
	private $fuzzy;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00200100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->startdate = get_var_value('startdate') == NULL? '': get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
		$this->type =  get_var_value('type') == NULL? 0 : get_var_value('type');
		$this->key =  get_var_value('key') == NULL? '' : get_var_value('key');
		$this->fuzzy =  get_var_value('fuzzy') == NULL? -1 : get_var_value('fuzzy');
	}
	
	/**
	 * 搜索消费记录
	 */
	public function search(){
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		
		$obj = D(GNAME.$this -> ip);
		$total = 0;							//查询总记录数
		$roleList = array();				//角色基本信息
		$total = $obj -> table('role')		//先从数据库取(主要查出角色名)
					  -> where(array('r_roleid =' => $this->key))
					  -> total();	
		if(intval($total) > 0){
			$roleList = $obj -> table('role')
						 -> field('r_roleid as id,r_name as name')
						 -> where(array('r_roleid' => $this -> key))
						 -> find();
		}else{				 
			$info = array();
			$info['queryMode'] = 1;			//账号(0) / ID(1) /精确昵称(2)
			$info['what'] = $this -> key;

			$callReasult = $this -> gm -> gm2000($info,$ip,$port,$loginName);	//调用gm接口
			if($callReasult != 'error'){
				$arr = explode("|",$callReasult);
				if($arr[1] != null){
					$roleList = json_decode($arr[1],true);
					if(isset($roleList['id'])){
						$obj->table('role')->insert(array(
							'r_roleid' => $roleList['id'],
							'r_name'   => $roleList['name'],
							'r_updatetime' => date('Y-m-d H:i:s')
						));
					}
				}
			}
		}
		
		$roleId = $this->key;
		if(isset($roleList['id'])) {
			$roleId = $roleList['id'];
		}
		$roleName = '暂无';
		if(isset($roleList['name'])){
			$roleName = $roleList['name'];
		}
		
		
	
		$dbId = $roleId % 15;
		
		$list = array(); 	//消费记录
		$total = 0;
		if(empty($this -> startdate) && empty($this->enddate)){
			$list = $obj -> table('item'.$dbId) 
						 -> where(array('i_playid' => $roleId))
						 ->limit(intval(($this->curPage - 1)*$this -> pageSize),intval($this -> pageSize))
						 -> select();
			$total = $obj -> table('item'.$dbId) 
						 -> where(array('i_playid' => $roleId))
						 -> total();			 
		}else{
			if($this -> startdate != $this->enddate){
				$enddate = date("Y-m-d",strtotime($this->enddate)+86400);
				$list = $obj -> table('item'.$dbId)
							 -> where(array('i_playid' => $roleId,'i_date >= ' => $this->startdate,'i_date < '=>$enddate)) 
							 -> limit(intval(($this->curPage - 1)*$this -> pageSize),intval($this -> pageSize))
							 -> select();
							 
				$total = $obj -> table('item'.$dbId)
							 -> where(array('i_playid' => $roleId,'i_date >= ' => $this->startdate,'i_date < '=>$enddate)) 
							 -> total();			 
			}else{
				$list = $obj -> table('item'.$dbId)
							 -> where(array('i_playid' => $roleId,'i_date like' => $this->startdate.'%'))
							 ->limit(intval(($this->curPage - 1)*$this -> pageSize),intval($this -> pageSize))		
							 -> select();
							 
				$total = $obj -> table('item'.$dbId)
							 -> where(array('i_playid' => $roleId,'i_date like ' => $this->startdate.'%'))		
							 -> total();			 
			}
		}
				
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		
		$point = D("game_info");				//获取物品名称
		$goods = $point -> table('goods_detail') -> select();
		$goods_arr = array();
		foreach($goods as $val) {
			$goods_arr[$val['g_code']] = $val['g_name'];
		}
		
		$result = array(
				'list' => $list,
				'pageHtml'=>$pageHtml,
				'roleName' => $roleName,
				'good_list' => $goods_arr
			);
		echo json_encode($result);
		exit;
		
	}
	
	//根据角色名获取角色ID
	public function getRoleList(){
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		
		$obj = D(GNAME.$this -> ip);
		
		if($this -> fuzzy == 0){ 		//模糊查询
			$total = 0;					//查询总记录数
			$roleList = array();		//玩家基本信息

			$info = array();
			$info['pageNum'] = $this -> curPage;
			$info['what'] = $this -> key;
			
			$callReasult = $this -> gm -> gm2003($info,$ip,$port,$loginName);//调用gm接口
			if($callReasult == 'error'){
				echo "{'error':'远程超时无响应！'}";
				exit;
			}
			$arr = explode("|",$callReasult);
			if($arr[1] != null){
				$info = json_decode($arr[1],true);
			}
			if(isset($info["totalPage"])){
				$total = $info["totalPage"] * $this->pageSize;
			}
			if(isset($info["players"])){
				$roleList = $info["players"];
			}

			$page = new autoAjaxPage($this -> pageSize,$this -> curPage,$total,"pageAjax2","go2","page2");
			$pageHtml = $page -> getPageHtml();
			$result = array(
					'pageHtml' 	=> $pageHtml,
					'plays' 	=> $roleList
			);
			if($this->curPage != 1) {			//请求延迟一秒执行
				sleep(1);
			}
			echo json_encode($result);
			exit;
		}else if($this->fuzzy == 1){			//精确查询
			$total = 0;							//查询总记录数
			$roleList = array();				//玩家基本信息
			$total = $obj -> table('role')		//先从数据库取
						  -> where(array('r_name =' => $this->key))
						  -> total();	
			if(intval($total) < 0){
				$roleList = $obj -> table('role')
							 -> field('r_roleid as id,r_name as name')
							 -> where(array('r_name ' => $this -> key))
							 ->select();
			}else{				 
				$info = array();
				$info['queryMode'] = 2;
				$info['what'] = $this -> key;

				$callReasult = $this -> gm -> gm2000($info,$ip,$port,$loginName);//调用gm接口
				if($callReasult == 'error'){
					echo "{'error':'远程超时无响应！'}";
					exit;
				}
				$arr = explode("|",$callReasult);
				if($arr[1] != null){
					$info = json_decode($arr[1],true);
					$roleList[] = $info;
				}
				$result = array(
					'pageHtml' 	=> '',
					'plays' => $roleList
				);
				if($this->curPage != 1) {
					sleep(1);
				}
				echo json_encode($result);
				exit;
			}
		}
	}

}