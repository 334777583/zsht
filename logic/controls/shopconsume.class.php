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
	*初始化数据
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
		$roleList = array();
		global $t_conf;
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);

		$obj = D("game".$this -> ip);
		$total = 0;							//查询总记录数
		$Reasult1 = $obj->fquery("SELECT i.i_playid,i.i_shopid,i.i_price,SUM(i.i_num) cnum,i.i_dtype,i.i_date,g.t_name FROM item as i LEFT JOIN goods_detail as g ON i.i_shopid=g.t_code WHERE i.i_type=3 AND i.i_date > '{$this->startdate}' AND i.i_date <= '{$this->enddate}' GROUP BY i.i_playid, i.i_shopid ORDER BY i.i_shopid ASC");

		if (!empty($this->key)) {
				$where = " WHERE GUID={$this->key}";
			}else{
				$where = '';
			}
			$Reasult2 = $point->fquery("SELECT GUID,RoleName,AccountId FROM player_table".$where);

			foreach ($Reasult1 as $key => $value) {
				foreach ($Reasult2 as $k => $v) {
					if ($Reasult1[$key]['i_playid'] == $Reasult2[$k]['GUID']) {
						$value['p_name'] = $Reasult2[$k]['RoleName'];
						$value['p_account'] = $Reasult2[$k]['AccountId'];
						$roleList[] = $value;
					}
				}
			}

	/*				//角色基本信息
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
		
		
	
		$dbId = $roleId % 15;		//角色id取余
		
		$list = array(); 	//消费记录
		$total = 0;
		if(empty($this -> startdate) && empty($this->enddate)){  //没有选择区间
			$list = $obj -> table('item'.$dbId) 
						 -> where(array('i_playid' => $roleId))
						 ->limit(intval(($this->curPage - 1)*$this -> pageSize),intval($this -> pageSize))
						 -> select();
			$total = $obj -> table('item'.$dbId) 
						 -> where(array('i_playid' => $roleId))
						 -> total();			 
		}else{
			if($this -> startdate != $this->enddate){    //开始时间与结束时间
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
				
		
		
		$point = D('game_base');				//获取商品名称
		$goods = $point -> table('goods_detail') -> select();
		$goods_arr = array(); 
		foreach($goods as $val) {
			$goods_arr[$val['g_code']] = $val['g_name'];
		}
	*/
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();

		$result = array(
				'list' => $roleList,
				'pageHtml'=>$pageHtml
				//'roleName' => $roleName,
				//'good_list' => $goods_arr
			);
		echo json_encode($result);
		exit;
	}
	
	/**
	*根据角色名获取角色ID
	*/
	public function getRoleList(){
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		global $t_conf;

		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		$obj = D("game".$this -> ip);
		
		if($this -> fuzzy == 0){ 		//模糊查询
			$total = 0;					//查询总记录数
			$roleList = array();		//玩家基本信息

			$info = array();
			$info['pageNum'] = $this -> curPage;
			$info['what'] = $this -> key;
			
		/*$callReasult = $this -> gm -> gm2003($info,$ip,$port,$loginName);//调用gm接口
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
				
			
		*/
			if (!empty($this->key)) {
				$where = " WHERE RoleName like '%{$this->key}%'";
			}else{
				$where = '';
			}
			$Reasult2 = $point->fquery("SELECT GUID,RoleName,AccountId FROM player_table".$where);
			
			$page = new autoAjaxPage($this -> pageSize,$this -> curPage,$total,"pageAjax2","go2","page2");

			$pageHtml = $page -> getPageHtml();
			$result = array(
					'pageHtml' 	=> $pageHtml,
					'plays' 	=> $Reasult2
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